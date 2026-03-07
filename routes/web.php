<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');


/*
|--------------------------------------------------------------------------
| USER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    /*
    |----------------------------
    | Admin Products
    |----------------------------
    */

    Route::get('/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');

    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');

    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');


    /*
    |----------------------------
    | Admin Orders
    |----------------------------
    */

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');


});


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |----------------------------
    | Product Catalog (USER)
    |----------------------------
    */

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');


    /*
    |----------------------------
    | Cart
    |----------------------------
    */

    Route::get('/cart', function () {

        $cart = session('cart', []);

        return view('cart.index', compact('cart'));

    })->name('cart.index');


    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');

    Route::post('/cart/{product}/increase', [CartController::class, 'increase'])->name('cart.increase');

    Route::post('/cart/{product}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');

    Route::post('/cart/{product}/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');


    /*
    |----------------------------
    | Orders (USER)
    |----------------------------
    */

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');


    /*
    |----------------------------
    | Profile
    |----------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});


require __DIR__.'/auth.php';