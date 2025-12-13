<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/cart', function () {
    $cart = session('cart', []);
    return view('cart.index', compact('cart'));
    })->name('cart.index')->middleware('auth');

    Route::post('/cart/{product}', [CartController::class, 'add'])
    ->name('cart.add')
    ->middleware('auth');

    Route::post('/cart/{product}/increase', [CartController::class, 'increase'])
    ->name('cart.increase')
    ->middleware('auth');

    Route::post('/cart/{product}/decrease', [CartController::class, 'decrease'])
    ->name('cart.decrease')
    ->middleware('auth');

    //Remove Items
    Route::post('/cart/{product}/remove', [CartController::class, 'remove'])
    ->name('cart.remove')
    ->middleware('auth');

    Route::post('/cart/checkout', [CartController::class, 'checkout'])
    ->name('cart.checkout')
    ->middleware('auth');






    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
