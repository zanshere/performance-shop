<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware('api')->group(function () {
    // Product search for AJAX
    Route::get('/products/search', [ProductController::class, 'searchAjax']);

    // Quick view product
    Route::get('/products/{id}/quick-view', function ($id) {
        $product = \App\Models\Product::findOrFail($id);

        $html = view('products.partials.quick-view', compact('product'))->render();

        return response()->json(['html' => $html]);
    });
});
