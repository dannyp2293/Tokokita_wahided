# Catatan Product, Policy & Flow – Tokokita

## 1. ProductController – Ringkasan Fungsi

Controller ini ngurus **CRUD produk** + search + upload gambar.

### 1.1. index(Request $request)

Tujuan:
- Menampilkan list produk
- Support **search** + **pagination**

Flow:
- Ambil nilai `search` dari query string (`$request->search`)
- Query `Product`:
  - kalau `search` ada → filter `nama` atau `harga` pakai `LIKE`
- Paginate hasil, misal `->paginate(10)`
- Kirim ke view `products.index` sebagai `$products`

Hal penting:
- View ini yang pakai **grid card produk**
- Di dalam card:
  - tampil nama, harga, stok, gambar
  - tombol Edit/Delete (untuk admin)
  - tombol Add to Cart (untuk user biasa)


### 1.2. create()

Tujuan:
- Menampilkan form untuk tambah produk baru

Flow:
- `$this->authorize('create', Product::class);`
  - hanya **superadmin** yang boleh akses
- return view `products.create`

Hal penting:
- Tidak ada logic berat di sini, cuma authorization + return view


### 1.3. store(Request $request)

Tujuan:
- Menerima data dari form **create product**
- Validasi
- Upload foto
- Simpan ke database

Flow umum:
1. `$this->authorize('create', Product::class);`
   - jaga supaya user biasa **tidak bisa** langsung POST ke route ini
2. Validasi input:
   - `nama` wajib
   - `harga` numeric
   - `stock` integer minimal 0
   - `foto` wajib dan harus image
3. Ambil file foto dari request
4. Pastikan folder `public/storage/products` ada (kalau belum, buat)
5. Simpan file foto ke folder itu dengan nama hash (`$foto->hashName()`)
6. `Product::create([...])`
   - simpan `nama`, `harga`, `stock`, `deskripsi`, `foto` (path relatif)
7. Redirect ke `products.index` dengan flash `success`

Catatan:
- Di **model Product**, field `nama`, `harga`, `stock`, `deskripsi`, `foto` harus ada di `$fillable`


### 1.4. edit(Product $product)

Tujuan:
- Menampilkan form edit untuk satu produk

Flow:
- `$this->authorize('update', $product);`
- return view `products.edit` dengan data `$product`

Catatan:
- Laravel pakai **Route Model Binding**:
  - route `products/{product}/edit` otomatis kirim instance `Product` ke parameter


### 1.5. update(Request $request, Product $product)

Tujuan:
- Menyimpan perubahan produk

Flow:
1. `$this->authorize('update', $product);`
2. Validasi input (mirip `store`, bedanya `foto` bisa nullable)
3. Update:
   - `nama`
   - `harga` (bisa diparsing dari format pakai `str_replace(".", "", $request->harga)`)
   - `stock`
   - `deskripsi`
4. Kalau ada file `foto` baru:
   - hapus foto lama di storage (kalau ada)
   - simpan foto baru
   - update field `foto` di DB
5. `$product->save()`
6. Redirect ke `products.index` dengan flash `success` "Update Product Success"


### 1.6. destroy(Product $product)

Tujuan:
- Menghapus satu produk

Flow:
1. `$this->authorize('delete', $product);`
2. Kalau ada foto → hapus file fotonya di `storage`
3. `$product->delete()`
4. Redirect ke `products.index` dengan flash `success`

Catatan:
- Di UI, tombol Delete hanya muncul untuk admin (`@can('delete', $product)`)



## 2. Authorization & Policy – Ringkasan

### 2.1. Peran ProductPolicy

Di `App\Policies\ProductPolicy`:

```php
public function create(User $user)
{
    return $user->role === 'superadmin';
}

public function update(User $user, Product $product)
{
    return $user->role === 'superadmin';
}

public function delete(User $user, Product $product)
{
    return $user->role === 'superadmin';
}
