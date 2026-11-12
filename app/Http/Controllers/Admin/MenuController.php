<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:100']]);

        return view('admin.menus.index', ['menus' => Menu::with('category')->when($data['q'] ?? null, fn ($q, $term) => $q->where('name', 'like', '%'.$term.'%'))->latest()->paginate(10)->withQueryString()]);
    }

    public function create(): View
    {
        return view('admin.menus.form', ['menu' => new Menu(['is_available' => true]), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(MenuRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $path = $request->file('image')?->store('menus', 'public');
        $data['image'] = $path;
        try {
            Menu::create($data);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            } throw $e;
        }

        return to_route('admin.menus.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.form', compact('menu') + ['categories' => Category::orderBy('name')->get()]);
    }

    public function update(MenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $old = $menu->image;
        $path = $request->file('image')?->store('menus', 'public');
        if ($path) {
            $data['image'] = $path;
        }
        try {
            $menu->update($data);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            } throw $e;
        }
        if ($path && $old) {
            Storage::disk('public')->delete($old);
        }

        return to_route('admin.menus.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $path = $menu->image;
        $menu->delete();
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Paket berhasil dihapus. Riwayat pesanan tetap tersimpan.');
    }
}
