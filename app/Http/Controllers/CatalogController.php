<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogController extends Controller
{
    public function home(): View
    {
        return view('shop.home', ['menus' => Menu::with('category')->where('is_available', true)->latest()->take(6)->get(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function index(Request $request): View
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'category' => ['nullable', 'integer']]);
        $menus = Menu::with('category')->where('is_available', true)->when($data['q'] ?? null, fn ($q, $term) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$term.'%')->orWhere('description', 'like', '%'.$term.'%')))->when($data['category'] ?? null, fn ($q, $id) => $q->where('category_id', $id))->latest()->paginate(9)->withQueryString();

        return view('shop.catalog', compact('menus') + ['categories' => Category::orderBy('name')->get()]);
    }

    public function show(Menu $menu): View
    {
        abort_unless($menu->is_available, 404);

        return view('shop.detail', compact('menu'));
    }

    public function image(Menu $menu): StreamedResponse
    {
        abort_unless($menu->image && Storage::disk('public')->exists($menu->image), 404);

        return Storage::disk('public')->response($menu->image, null, ['X-Content-Type-Options' => 'nosniff']);
    }

    public function about(): View
    {
        return view('shop.about');
    }
}
