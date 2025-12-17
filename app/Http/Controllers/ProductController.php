<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with search and filtering
     */
    public function index(Request $request)
    {
        $query = Product::active();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->search($search);
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->brand($request->input('brand'));
        }

        // Filter by category
        if ($request->has('category')) {
            $query->category($request->input('category'));
        }

        // Filter by price range
        if ($request->has('min_price') || $request->has('max_price')) {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $query->priceRange($minPrice, $maxPrice);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get all brands and categories for filters
        $brands = Brand::active()->get();
        $categories = Category::active()->get();

        // Get min and max price for price range filter
        $priceRange = Product::active()
            ->select(DB::raw('MIN(price) as min_price'), DB::raw('MAX(price) as max_price'))
            ->first();

        // Pagination
        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'brands', 'categories', 'priceRange'));
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = Product::active()->findOrFail($id);

        // Get related products (same category)
        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Get products by category
     */
    public function byCategory($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $products = Product::active()
            ->where('category', $category->name)
            ->paginate(12);

        return view('products.category', compact('products', 'category'));
    }

    /**
     * Get products by brand
     */
    public function byBrand($brandSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->firstOrFail();
        $products = Product::active()
            ->where('brand', $brand->name)
            ->paginate(12);

        return view('products.brand', compact('products', 'brand'));
    }

    /**
     * Search products with AJAX
     */
    public function searchAjax(Request $request)
    {
        $search = $request->input('q');

        $products = Product::active()
            ->search($search)
            ->limit(5)
            ->get(['id', 'name', 'price', 'images', 'slug']);

        return response()->json($products);
    }
}
