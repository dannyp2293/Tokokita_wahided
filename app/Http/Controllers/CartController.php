<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Product $product)
{
    $cart = session()->get('cart', []);

    $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['qty'] : 0;

    // CEK STOK
    if ($product->stock <= $currentQty) {
        return back()->with('error', 'Stok '.$product->nama.' tidak mencukupi');
    }

    if (isset($cart[$product->id])) {
        $cart[$product->id]['qty']++;
    } else {
        $cart[$product->id] = [
            'nama'  => $product->nama,
            'harga'=> $product->harga,
            'qty'   => 1,
        ];
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Produk ditambahkan ke keranjang');
}

public function increase(Product $product)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {

        $currentQty = $cart[$product->id]['qty'];

        // CEK STOK sebelum tambah
        if ($product->stock <= $currentQty) {
            return back()->with('error', 'Stok '.$product->nama.' tidak mencukupi');
        }

        $cart[$product->id]['qty']++;
        session()->put('cart', $cart);
    }

    return back();
}

public function decrease(Product $product)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['qty']--;

        // kalau qty 0, hapus item
        if ($cart[$product->id]['qty'] <= 0) {
            unset($cart[$product->id]);
        }
    }

    session()->put('cart', $cart);

    return back();
}

public function remove(Product $product)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        unset($cart[$product->id]);
    }

    session()->put('cart', $cart);

    return back();
}

public function checkout()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Keranjang masih kosong');
    }

    $productIds = array_keys($cart);

    // Ambil produk dari DB
    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    // 1) CEK STOK DULU
    foreach ($cart as $id => $item) {
        /** @var Product|null $product */
        $product = $products[$id] ?? null;

        if (! $product) {
            return back()->with('error', 'Produk dengan ID '.$id.' tidak ditemukan');
        }

        if ($product->stock < $item['qty']) {
            return back()->with('error', 'Stok '.$product->nama.' tidak mencukupi. Tersedia: '.$product->stock);
        }
    }

    // 2) KURANGI STOK
    foreach ($cart as $id => $item) {
        $product = $products[$id];
        $product->decrement('stock', $item['qty']); // UPDATE stok di DB
    }

    // 3) KOSONGKAN CART
    session()->forget('cart');

    return back()->with('success', 'Checkout berhasil (dummy, belum simpan order).');
}


}
