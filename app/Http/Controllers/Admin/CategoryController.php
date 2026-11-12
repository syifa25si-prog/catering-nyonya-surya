<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('menus')->orderBy('name')->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return to_route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menus()->exists()) {
            return back()->withErrors(['category' => 'Kategori masih memiliki paket. Pindahkan atau hapus paket terlebih dahulu.']);
        }
        try {
            $category->delete();
        } catch (QueryException $exception) {
            if ($category->menus()->exists()) {
                return back()->withErrors(['category' => 'Kategori masih digunakan oleh paket.']);
            } throw $exception;
        }

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
