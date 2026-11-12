<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public static function contents(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $menus = Menu::with('category')->whereIn('id', array_keys($cart))->get();
        $missing = array_diff(array_keys($cart), $menus->modelKeys());
        $items = $menus->map(fn ($menu) => ['menu' => $menu, 'quantity' => (int) $cart[$menu->id], 'subtotal' => $menu->price * (int) $cart[$menu->id]]);

        return ['items' => $items, 'total' => $items->sum('subtotal'), 'missing' => $missing, 'unavailable' => $items->contains(fn ($item) => ! $item['menu']->is_available) || count($missing) > 0];
    }

    public function index(Request $request): View
    {
        return view('shop.cart', self::contents($request));
    }

    public function store(Request $request, Menu $menu): RedirectResponse
    {
        abort_unless($menu->is_available, 404);
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:9999']]);
        $cart = $request->session()->get('cart', []);
        $quantity = ($cart[$menu->id] ?? 0) + $data['quantity'];
        if ($quantity > 9999) {
            return back()->withErrors(['quantity' => 'Maksimal 9999 porsi per paket.']);
        }
        $cart[$menu->id] = $quantity;
        $request->session()->put('cart', $cart);
        $request->session()->forget('checkout_token');

        return to_route('cart.index')->with('success', 'Paket ditambahkan ke keranjang.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:9999']]);
        $cart = $request->session()->get('cart', []);
        abort_unless(isset($cart[$menu->id]), 404);
        $cart[$menu->id] = $data['quantity'];
        $request->session()->put('cart', $cart);
        $request->session()->forget('checkout_token');

        return back()->with('success', 'Jumlah paket diperbarui.');
    }

    public function destroy(Request $request, string $menu): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$menu]);
        $request->session()->put('cart', $cart);
        $request->session()->forget('checkout_token');

        return back()->with('success', 'Paket dihapus dari keranjang.');
    }
}
