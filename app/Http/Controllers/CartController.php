<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
public function add(Product $product)
{
     // 1. Ambil keranjang dari session
    $cart = session()->get('cart', []);

    // 2. Hitung qty produk ini yang sudah ada di keranjang
    $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['qty'] : 0;

    // 3. Cek stok, kalau sudah habis jangan boleh tambah
    if ($product->stock <= $currentQty) {
        return back()->with('error', 'Stok '.$product->nama.' tidak mencukupi');
    }

    // 4. Kalau sudah ada, tambah qty. Kalau belum, buat item baru
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
    //  dd('MASUK CHECKOUT', session('cart'));
    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Keranjang masih kosong');
    }

    $productIds = array_keys($cart);

    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    try {
        DB::beginTransaction();

        // 1. Cek stok dulu
        foreach ($cart as $id => $item) {
            $product = $products[$id] ?? null;

            if (! $product) {
                return back()->with('error', "Produk dengan ID $id tidak ditemukan");
            }

            if ($product->stock < $item['qty']) {
                return back()->with(
                    'error',
                    'Stok '.$product->nama.' tidak mencukupi. Tersedia: '.$product->stock
                );
            }
        }

        // 2. Hitung total
        $total = 0;
        foreach ($cart as $id => $item) {
            $total += $item['harga'] * $item['qty'];
        }

        // 3. Buat order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total'   => $total,
            'status'  => 'pending',
        ]);

        // 4. Buat order_items + kurangi stok
        foreach ($cart as $id => $item) {
            $product = $products[$id];

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'qty'        => $item['qty'],
                'price'      => $item['harga'],
                'subtotal'   => $item['harga'] * $item['qty'],
            ]);

            $product->decrement('stock', $item['qty']);
        }

        // 5. Kosongkan cart
        session()->forget('cart');

        DB::commit();

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat checkout');
        // dd($e->getMessage());
    }

    return redirect()
        ->route('orders.show', $order)
        ->with('success', 'Checkout berhasil. Terima kasih!');
}

}
