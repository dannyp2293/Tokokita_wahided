<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ProductController extends Controller
{
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

   public function store(Request $request)
{
    $validated = $request->validate([
        'nama'      => 'required',
        'harga'     => 'required|numeric',
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
        'nama'      => $request->nama,
        'harga'     => $request->harga,
        'deskripsi' => $request->deskripsi,
        'foto'      => 'products/'.$filename,   // simpan path relatif
    ]);

    return redirect()->route('products.index')->with('success', 'Product Berhasil Ditambahkan');
}

public function edit (Product $product)
{
    return view('products.edit',compact('product'));
}
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'nama'      => 'required',
        'harga'     => 'required|numeric',
        'deskripsi' => 'nullable',
        'foto'      => 'nullable|image|mimes:jpeg,png,jpg',
    ]);
    // dd(str_replace(".","",$request->harga));
    $product->nama      = $request->nama;
    $product->harga     = (str_replace(".","",$request->harga));
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
    if ($product->foto) {
        Storage::disk('public')->delete($product->foto);
    }

    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('success', 'Product berhasil dihapus!');
}



}
