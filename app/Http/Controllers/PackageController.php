<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index(Request $request)
    {
        $query = Package::active();

        // Filter by category
        if ($request->has('category')) {
            $query->category($request->category);
        }

        // Filter by difficulty level
        if ($request->has('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Filter by price range
        if ($request->has('min_price') || $request->has('max_price')) {
            if ($request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'popular');
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
            case 'popular':
                $query->orderBy('order_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $packages = $query->paginate(9);

        // Get categories for filter
        $categories = Package::active()
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Get difficulty levels for filter
        $difficultyLevels = Package::active()
            ->select('difficulty_level')
            ->distinct()
            ->pluck('difficulty_level');

        // Get price range for filter
        $priceRange = Package::active()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        // Get featured packages for sidebar
        $featuredPackages = Package::active()
            ->featured()
            ->limit(3)
            ->get();

        return view('packages.index', compact(
            'packages',
            'categories',
            'difficultyLevels',
            'priceRange',
            'featuredPackages'
        ));
    }

    /**
     * Display the specified package
     */
    public function show($slug)
    {
        $package = Package::where('slug', $slug)->firstOrFail();

        // Increment view count
        $package->incrementViewCount();

        // Get related packages (same category)
        $relatedPackages = Package::active()
            ->where('category', $package->category)
            ->where('id', '!=', $package->id)
            ->limit(3)
            ->get();

        // Get recommended products for this package
        $recommendedProducts = $this->getRecommendedProducts($package);

        return view('packages.show', compact(
            'package',
            'relatedPackages',
            'recommendedProducts'
        ));
    }

    /**
     * Get recommended products for a package
     */
    private function getRecommendedProducts(Package $package)
    {
        // Based on package category, recommend relevant products
        $categories = [];

        switch ($package->category) {
            case 'harian':
                $categories = ['Transmisi CVT', 'Mesin'];
                break;
            case 'sport':
                $categories = ['Mesin', 'Kelistrikan', 'Bahan Bakar'];
                break;
            case 'racing':
                $categories = ['Mesin', 'Kelistrikan', 'Bahan Bakar', 'Sensor'];
                break;
            case 'custom':
                $categories = ['Transmisi CVT', 'Mesin', 'Kelistrikan', 'Bahan Bakar', 'Sensor'];
                break;
        }

        return Product::active()
            ->whereIn('category', $categories)
            ->where('is_featured', true)
            ->limit(6)
            ->get();
    }

    /**
     * Package inquiry form
     */
    public function inquiry(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'bike_model' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        $package = Package::findOrFail($id);

        // Here you would typically:
        // 1. Save inquiry to database
        // 2. Send email notification
        // 3. Send confirmation to customer

        return back()->with('success', 'Permintaan konsultasi berhasil dikirim. Tim kami akan menghubungi Anda dalam 1x24 jam.');
    }

    /**
     * Compare packages
     */
    public function compare(Request $request)
    {
        $packageIds = $request->input('packages', []);

        if (count($packageIds) < 2 || count($packageIds) > 4) {
            return redirect()->route('packages.index')
                ->with('error', 'Pilih 2-4 paket untuk dibandingkan');
        }

        $packages = Package::whereIn('id', $packageIds)->get();

        // Get comparison features
        $comparisonFeatures = [
            'Harga',
            'Kategori',
            'Durasi Pengerjaan',
            'Tingkat Kesulitan',
            'Peningkatan Daya',
            'Jumlah Item Termasuk',
            'Jumlah Pesanan',
            'Status'
        ];

        return view('packages.compare', compact('packages', 'comparisonFeatures'));
    }
}
