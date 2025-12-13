<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    use AuthorizesRequests;
public function index(Request $request)
{
    $search = $request->search;

    $products = Product::when($search, function ($query) use ($search) {
        $query->where('nama', 'like', "%{$search}%")
              ->orWhere('harga', 'like', "%{$search}%");
    })
    ->paginate(10)
    ->withQueryString(); // supaya pagination tetap membawa query search

    return view('products.index', compact('products'));
}

public function create()
{
      $this->authorize('create', Product::class);
    return view('products.create');
}


   public function store(Request $request)
{
      $this->authorize('create', Product::class);
    $validated = $request->validate([
        'nama'      => 'required',
        'harga'     => 'required|numeric',
        'stock'     => 'required|integer|min:0',
        'deskripsi' => 'nullable',
        'foto'      => 'required|image|mimes:jpeg,png,jpg',
    ]);

    $foto = $request->file('foto');

    // pastikan foldernya ada
    if (! file_exists(public_path('storage/products'))) {
        mkdir(public_path('storage/products'), 0777, true);
    }

    // simpan LANGSUNG ke public/storage/products
    $filename = $foto->hashName();
    $foto->move(public_path('storage/products'), $filename);

    Product::create([
        'nama'      => $validated['nama'],
        'harga'     => $validated['harga'],
        'stock'     => $validated['stock'],
        'deskripsi' => $validated['deskripsi'] ?? null,
        'foto'      => 'products/'.$filename,   // simpan path relatif
    ]);
    return redirect()->route('products.index')->with('success', 'Product Berhasil Ditambahkan');
}

public function edit (Product $product)
{
     $this->authorize('update', $product);
    return view('products.edit', compact('product'));
}
public function update(Request $request, Product $product)
{
        $this->authorize('update', $product);
    $validated = $request->validate([
        'nama'      => 'required',
        'harga'     => 'required|numeric',
        'stock'     => 'required|integer|min:0',
        'deskripsi' => 'nullable',
        'foto'      => 'nullable|image|mimes:jpeg,png,jpg',
    ]);
    // dd(str_replace(".","",$request->harga));
    $product->nama      = $request->nama;
    $product->harga     = (str_replace(".","",$request->harga));
        $product->stock     = $request->stock;
    $product->deskripsi = $request->deskripsi;

    if ($request->hasFile('foto')) {

        // hapus foto lama kalau ada
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        $foto = $request->file('foto');
        $foto->storeAs('products', $foto->hashName(), 'public');

        $product->foto = 'products/'.$foto->hashName();  // SAMA dengan store()
    }

    $product->save();

    return redirect()->route('products.index')->with('success', 'Update Product Success');
}
public function destroy(Product $product)
{

     $this->authorize('delete', $product);

    if ($product->foto) {
        Storage::disk('public')->delete($product->foto);
    }

    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('success', 'Product berhasil dihapus!');
}



}
