# Catatan Cart & Stok – Tokokita

## 1. Struktur Cart di Session

- Cart disimpan di **session** dengan key: `cart`
- Bentuk datanya:

  cart = [
    product_id => [
      'nama'  => string,
      'harga' => int,
      'qty'   => int,
    ],
    ...
  ]

- Jadi:
  - key = `product_id`
  - value = info produk + `qty` di keranjang


## 2. Alur Umum Cart

1. User klik **Add to Cart** di halaman Products
2. Cart disimpan / diupdate di **session**
3. Di halaman **Cart**:
   - tampil list item
   - bisa `+` (increase qty)
   - bisa `-` (decrease qty)
   - bisa ❌ (remove)
   - total harga dihitung dari `harga * qty`
4. Saat **Checkout**:
   - cek keranjang kosong atau tidak
   - cek stok cukup atau tidak
   - kalau cukup → stok di DB dikurangi
   - cart dikosongkan


## 3. CartController – Ringkasan Method

### add(Product $product)
Dipanggil saat klik **Add to Cart** di card produk.

Flow:
- Ambil cart dari session: `session()->get('cart', [])`
- Hitung qty yang sudah ada di cart untuk produk itu (`$currentQty`)
- Cek stok:
  - kalau `product->stock <= currentQty` → tolak
  - kirim flash `error` (stok tidak cukup)
- Kalau produk sudah ada di cart → `qty++`
- Kalau belum ada → buat item baru dengan `qty = 1`
- Simpan lagi ke session: `session()->put('cart', $cart)`
- Redirect back dengan flash `success`: "Produk ditambahkan ke keranjang"


### increase(Product $product)
Dipanggil saat klik tombol **+** di halaman Cart.

Flow:
- Ambil cart dari session
- Pastikan item produk ini ada di cart
- Ambil `currentQty`
- Cek stok:
  - kalau `product->stock <= currentQty` → tolak
  - kirim flash `error` (stok tidak cukup)
- Kalau stok masih cukup → `qty++`
- Simpan lagi ke session
- Redirect back (kembali ke cart)


### decrease(Product $product)
Dipanggil saat klik tombol **-** di halaman Cart.

Flow:
- Ambil cart dari session
- Kalau item ada:
  - `qty--`
  - kalau `qty <= 0` → `unset($cart[product_id])` (hapus item dari cart)
- Simpan lagi ke session
- Redirect back


### remove(Product $product)
Dipanggil saat klik tombol ❌ di halaman Cart.

Flow:
- Ambil cart dari session
- Kalau item ada → `unset($cart[product_id])`
- Simpan lagi ke session
- Redirect back


### checkout()
Checkout versi simple (belum simpan ke tabel orders).

Flow:
- Ambil cart dari session
- Kalau cart kosong → flash `error` "Keranjang masih kosong"
- Ambil semua `product_id` dari cart (`array_keys`)
- Query produk dari DB: `Product::whereIn('id', $productIds)->get()->keyBy('id')`
- Loop 1: **cek stok**
  - kalau produk tidak ditemukan → error
  - kalau `product->stock < qty di cart` → error (stok tidak cukup)
- Loop 2: **kurangi stok**
  - `product->decrement('stock', qty)`
- Kosongkan cart: `session()->forget('cart')`
- Redirect (biasanya ke cart) dengan flash `success` "Checkout berhasil"


## 4. Stok Produk – Alur Singkat

- Kolom `stock` ditambahkan di tabel `products`
- Saat **create/update product**:
  - field `stock` ikut di-validate dan di-save
- Di card produk (`products.index`):
  - tampil: `Stok: {{ $product->stock }}`
  - kalau `stock > 0` → tombol hijau **Add to Cart**
  - kalau `stock == 0` → tombol abu **Stok Habis** (tidak bisa klik)
- Di CartController:
  - `add()` dan `increase()` selalu cek stok vs qty di cart
  - `checkout()` cek stok sebelum kurangi di database


## 5. Authorization Singkat (Admin vs User)

- Di `ProductPolicy`:
  - `create/update/delete` hanya boleh jika `user->role === 'superadmin'`
- Di **controller**:
  - `store`, `update`, `destroy` pakai `$this->authorize(...)`
- Di **Blade**:
  - Tombol **Add Product** dan **Edit/Delete** dibungkus dengan `@can(...)`
  - User biasa:
    - tidak lihat tombol Add/Edit/Delete
    - cuma lihat produk + Add to Cart
  - Admin:
    - bisa CRUD produk, tapi tidak pakai cart
