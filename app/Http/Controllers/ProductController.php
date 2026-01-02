<?php

// Menentukan lokasi controller ini berada
namespace App\Http\Controllers;

// Memanggil model Product
use App\Models\Product;

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
    public function index(Request $request)
    {
        // Ambil keyword pencarian dari input search
        $search = $request->search;

        // Query produk + fitur search
        $products = Product::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('harga', 'like', "%{$search}%");
        })
        ->paginate(10) // tampilkan 10 data per halaman
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
        // Cek authorization
        $this->authorize('create', Product::class);

        // Validasi input dari form
        $validated = $request->validate([
            'nama'      => 'required',
            'harga'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'foto'      => 'required|image|mimes:jpeg,png,jpg',
        ]);

        // Ambil file foto
        $foto = $request->file('foto');

        // Pastikan folder penyimpanan ada
        if (! file_exists(public_path('storage/products'))) {
            mkdir(public_path('storage/products'), 0777, true);
        }

        // Simpan file ke folder public/storage/products
        $filename = $foto->hashName();
        $foto->move(public_path('storage/products'), $filename);

        // Simpan data ke database
        Product::create([
            'nama'      => $validated['nama'],
            'harga'     => $validated['harga'],
            'stock'     => $validated['stock'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto'      => 'products/' . $filename,
        ]);

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
        // Cek izin update
        $this->authorize('update', $product);

        // Validasi data
        $validated = $request->validate([
            'nama'      => 'required',
            'harga'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        // Update data biasa
        $product->nama = $request->nama;
        $product->harga = str_replace(".", "", $request->harga);
        $product->stock = $request->stock;
        $product->deskripsi = $request->deskripsi;

        // Jika user upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $foto->storeAs('products', $foto->hashName(), 'public');

            // Simpan path foto ke database
            $product->foto = 'products/' . $foto->hashName();
        }

        // Simpan perubahan
        $product->save();

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
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        // Hapus data dari database
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product berhasil dihapus!');
    }
}
