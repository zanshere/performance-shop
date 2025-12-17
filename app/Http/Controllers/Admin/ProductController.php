<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $products = $query->paginate(15);
        $brands = Brand::active()->get();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $brands = Brand::active()->get();
        $categories = Category::active()->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'compatibility' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        // Handle specifications JSON
        if ($request->has('specifications')) {
            $specs = [];
            $specLines = explode("\n", $request->specifications);
            foreach ($specLines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) == 2) {
                    $specs[trim($parts[0])] = trim($parts[1]);
                }
            }
            $validated['specifications'] = json_encode($specs);
        }

        // Handle compatibility JSON
        if ($request->has('compatibility')) {
            $compatibility = array_map('trim', explode(',', $request->compatibility));
            $validated['compatibility'] = json_encode($compatibility);
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);

        // Add images to validated data
        if (!empty($images)) {
            $validated['images'] = json_encode($images);
        }

        // Create product
        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $brands = Brand::active()->get();
        $categories = Category::active()->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'compatibility' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Handle image removal
        if ($request->has('remove_images')) {
            $currentImages = $product->images ? json_decode($product->images, true) : [];
            $removeImages = $request->remove_images;

            foreach ($removeImages as $image) {
                if (($key = array_search($image, $currentImages)) !== false) {
                    // Delete file from storage
                    Storage::disk('public')->delete($image);
                    unset($currentImages[$key]);
                }
            }
            $currentImages = array_values($currentImages); // Reindex array
            $validated['images'] = json_encode($currentImages);
        } else {
            $validated['images'] = $product->images;
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $currentImages = $validated['images'] ? json_decode($validated['images'], true) : [];

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $currentImages[] = $path;
            }

            $validated['images'] = json_encode($currentImages);
        }

        // Handle specifications JSON
        if ($request->has('specifications')) {
            $specs = [];
            $specLines = explode("\n", $request->specifications);
            foreach ($specLines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) == 2) {
                    $specs[trim($parts[0])] = trim($parts[1]);
                }
            }
            $validated['specifications'] = json_encode($specs);
        }

        // Handle compatibility JSON
        if ($request->has('compatibility')) {
            $compatibility = array_map('trim', explode(',', $request->compatibility));
            $validated['compatibility'] = json_encode($compatibility);
        }

        // Update product
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product images from storage
        if ($product->images) {
            $images = json_decode($product->images, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada produk yang dipilih.');
        }

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Produk berhasil diaktifkan.';
                break;

            case 'deactivate':
                Product::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Produk berhasil dinonaktifkan.';
                break;

            case 'delete':
                $products = Product::whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    // Delete images
                    if ($product->images) {
                        $images = json_decode($product->images, true);
                        foreach ($images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                    $product->delete();
                }
                $message = 'Produk berhasil dihapus.';
                break;

            default:
                return back()->with('error', 'Aksi tidak valid.');
        }

        return back()->with('success', $message);
    }

    /**
     * Export products
     */
    public function export(Request $request)
    {
        // In a real application, you would generate CSV or Excel file
        // For now, return JSON response
        $products = Product::all();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Data produk siap diunduh.'
        ]);
    }
}
