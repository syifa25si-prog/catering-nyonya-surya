<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function checkout(Request $request): View|RedirectResponse
    {
        $contents = CartController::contents($request);
        if ($contents['items']->isEmpty() || $contents['unavailable']) {
            return to_route('cart.index')->withErrors(['cart' => 'Periksa keranjang. Hapus paket yang tidak tersedia sebelum melanjutkan.']);
        }
        if (! $request->session()->has('checkout_token')) {
            $request->session()->put('checkout_token', (string) Str::uuid());
        }

        return view('shop.checkout', $contents);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $cart = $request->session()->get('cart', []);
        if (! $cart || ! hash_equals((string) $request->session()->get('checkout_token', ''), $data['checkout_token'])) {
            return to_route('cart.index')->withErrors(['cart' => 'Keranjang berubah atau pesanan sudah dikirim. Silakan periksa kembali.']);
        }
        $order = DB::transaction(function () use ($request, $data, $cart) {
            $menus = Menu::whereIn('id', array_keys($cart))->lockForUpdate()->get();
            if ($menus->count() !== count($cart) || $menus->contains(fn ($menu) => ! $menu->is_available)) {
                throw ValidationException::withMessages(['cart' => 'Ada paket yang sudah tidak tersedia. Periksa keranjang Anda.']);
            }
            $total = $menus->sum(fn ($menu) => $menu->price * $cart[$menu->id]);
            $order = Order::create(collect($data)->except('checkout_token')->all() + ['user_id' => $request->user()->id, 'number' => 'NS-'.now()->format('ymd').'-'.Str::upper(Str::random(8)), 'total' => $total, 'status' => 'Menunggu']);
            foreach ($menus as $menu) {
                $order->items()->create(['menu_id' => $menu->id, 'menu_name' => $menu->name, 'price' => $menu->price, 'quantity' => $cart[$menu->id], 'subtotal' => $menu->price * $cart[$menu->id]]);
            }

            return $order;
        });
        $request->session()->forget(['cart', 'checkout_token']);

        return to_route('orders.show', $order)->with('success', 'Pesanan berhasil disimpan! Admin akan memproses pesanan Anda.');
    }

    public function index(Request $request): View
    {
        return view('orders.index', ['orders' => Order::where('user_id', $request->user()->id)->latest()->paginate(10)]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', ['order' => $order->load('items'), 'admin' => false]);
    }
}
