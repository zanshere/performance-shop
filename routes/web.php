<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Packages
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/packages/{id}/inquiry', [PackageController::class, 'inquiry'])->name('packages.inquiry');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
});

// Orders Routes
Route::middleware(['auth'])->prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
    Route::get('/{id}/reorder', [OrderController::class, 'reorder'])->name('reorder');
});

// User Profile (Optional - jika ingin ada halaman profil)
Route::middleware(['auth'])->get('/profile', function () {
    return view('profile');
})->name('profile');

// Track Order (for guest)
Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::post('/track-order', [OrderController::class, 'track'])->name('orders.track.post');

// Midtrans Notification Route
Route::post('/midtrans/notification', [CartController::class, 'notification'])->name('midtrans.notification');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('/products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('/products/export', [AdminProductController::class, 'export'])->name('products.export');

    // Orders
    Route::resource('orders', AdminOrderController::class);
    Route::post('/orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/statistics', [AdminOrderController::class, 'statistics'])->name('orders.statistics');

    // Packages
    Route::resource('packages', AdminPackageController::class);

    // Statistics
    Route::get('/statistics', [AdminDashboardController::class, 'statistics'])->name('statistics');
});

// Auth Routes
require __DIR__.'/auth.php';
