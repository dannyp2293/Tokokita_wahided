<?php

// Menentukan lokasi controller ini berada
namespace App\Http\Controllers;

// Memanggil model Product
use App\Models\Product;
use App\Models\ProductImage;

// Untuk mengelola file (upload / hapus)
use Illuminate\Support\Facades\Storage;

// Untuk fitur authorization (policy)
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Untuk menangani request dari user
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Mengaktifkan fitur authorization (policy)
    use AuthorizesRequests;

    /**
     * Menampilkan daftar produk
     */

    public function adminIndex()
{
    $products = Product::latest()->get();

    return view('admin.products.index', compact('products'));
}
    public function index(Request $request)
    {
        // Ambil keyword pencarian dari input search
        $search = $request->search;

        // Query produk + fitur search
        $products = Product::with('images')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap membawa parameter search

        // Kirim data ke view
        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan halaman tambah produk
     */
    public function create()
    {
        // Cek apakah user punya izin membuat produk
        $this->authorize('create', Product::class);


        return view('products.create');
    }

    /**
     * Menyimpan data produk baru ke database
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'nama'      => 'required',
            'harga'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'images.*'  => 'image|mimes:jpeg,png,jpg'
        ]);

        // buat product dulu
        $product = Product::create([
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'stock' => $validated['stock'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // upload multiple images
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Berhasil Ditambahkan');
    }
    /**
     * Menampilkan halaman edit produk
     */
    public function edit(Product $product)
    {
        // Cek izin update
        $this->authorize('update', $product);

        return view('products.edit', compact('product'));
    }

    /**
     * Mengupdate data produk
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'nama'      => 'required',
            'harga'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'images.*'  => 'image|mimes:jpeg,png,jpg'
        ]);


        $product->update([
            'nama' => $request->nama,
            'harga' => str_replace(".", "", $request->harga),
            'stock' => $request->stock,
            'deskripsi' => $request->deskripsi
        ]);


        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Update Product Success');
    }
    /**
     * Menghapus produk
     */
    public function destroy(Product $product)
    {
        // Cek izin hapus
        $this->authorize('delete', $product);

        // Hapus foto jika ada
            foreach ($product->images as $image) {

            Storage::disk('public')->delete($image->image);

            $image->delete();

}
        // Hapus data dari database
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product berhasil dihapus!');
    }
}
