<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['status' => ['nullable', Rule::in(Order::STATUSES)], 'q' => ['nullable', 'string', 'max:100']]);

        return view('admin.orders.index', ['orders' => Order::with('user')->when($data['status'] ?? null, fn ($q, $s) => $q->where('status', $s))->when($data['q'] ?? null, fn ($q, $term) => $q->where(fn ($q) => $q->where('number', 'like', '%'.$term.'%')->orWhere('customer_name', 'like', '%'.$term.'%')))->latest()->paginate(10)->withQueryString()]);
    }

    public function show(Order $order): View
    {
        return view('orders.show', ['order' => $order->load('items', 'user'), 'admin' => true]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update($request->validate(['status' => ['required', Rule::in(Order::STATUSES)]]));

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
