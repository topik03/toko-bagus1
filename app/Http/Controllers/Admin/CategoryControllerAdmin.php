<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryControllerAdmin extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $categoryData = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/categories', $imageName);
            $categoryData['image_path'] = 'categories/' . $imageName;
        }

        Category::create($categoryData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->loadCount('parent', 'children', 'products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $category->loadCount('products');
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update slug if name changed
        if ($category->name != $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $category->slug = $slug;
        }

        $categoryData = [
            'name' => $request->name,
            'slug' => $category->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active ?? $category->is_active,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image_path && file_exists(storage_path('app/public/' . $category->image_path))) {
                unlink(storage_path('app/public/' . $category->image_path));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/categories', $imageName);
            $categoryData['image_path'] = 'categories/' . $imageName;
        }

        $category->update($categoryData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki produk.');
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki subkategori.');
        }

        // Delete image if exists
        if ($category->image_path && file_exists(storage_path('app/public/' . $category->image_path))) {
            unlink(storage_path('app/public/' . $category->image_path));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Update category status (active/inactive)
     */
    public function updateStatus(Request $request, Category $category)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $category->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kategori berhasil diperbarui.',
            'is_active' => $category->is_active,
        ]);
    }
}
