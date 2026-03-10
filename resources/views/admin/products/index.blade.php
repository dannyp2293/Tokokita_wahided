@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10">

        <div class="flex justify-between items-center mb-8">

            <h1 class="text-2xl font-bold text-gray-700">
                Products Management
            </h1>



        </div>

        <div class="grid grid-cols-4 gap-6 mb-8">

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Products</p>
                <p class="text-2xl font-bold">{{ $products->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">In Stock</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $products->where('stock', '>', 0)->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Out of Stock</p>
                <p class="text-2xl font-bold text-red-500">
                    {{ $products->where('stock', 0)->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Value</p>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($products->sum('harga')) }}
                </p>
            </div>

        </div>

        <div class="flex justify-between items-center mb-6">

            <div class="flex gap-3">

                <input type="text" placeholder="Search product..." class="border rounded-lg px-4 py-2 w-64">

                <select class="border rounded-lg px-3 py-2">
                    <option>All Stock</option>
                    <option>Available</option>
                    <option>Out of Stock</option>
                </select>

            </div>

            <a href="{{ route('admin.products.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg">
                + Add Product
            </a>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">

            @forelse($products as $product)
                <div class="bg-white rounded-xl shadow hover:shadow-xl hover:-translate-y-1 transition p-4">

                    {{-- IMAGE --}}
                    @if ($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                            class="w-full h-40 object-cover rounded-lg mb-3">
                    @else
                        <div class="w-full h-40 bg-gray-200 rounded-lg mb-3"></div>
                    @endif


                    {{-- NAME --}}
                    <h2 class="font-semibold text-gray-700">
                        {{ $product->nama }}
                    </h2>


                    {{-- PRICE --}}
                    <p class="text-gray-500 text-sm">
                        Rp {{ number_format($product->harga) }}
                    </p>


                    {{-- STOCK --}}
                    @if ($product->stock > 0)
                        <span class="inline-block mt-2 bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                            {{ $product->stock }} available
                        </span>
                    @else
                        <span class="inline-block mt-2 bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                            Out of Stock
                        </span>
                    @endif


                    {{-- BUTTON --}}
                    <div class="flex justify-between items-center mt-4">

                        <a href="{{ route('admin.products.edit', $product->id) }}"
                            class="text-blue-500 hover:text-blue-700 text-sm">
                            ✏ Edit
                        </a>

                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                            onsubmit="return confirm('Delete this product?')">

                            @csrf
                            @method('DELETE')

                            <button class="text-red-500 hover:text-red-700 text-sm">
                                🗑 Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-20">

                    <p class="text-4xl mb-4">
                        📦
                    </p>

                    <p class="text-gray-500 text-lg">
                        No Products Yet
                    </p>

                    <p class="text-gray-400 text-sm mb-6">
                        Start by adding your first product
                    </p>

                    <a href="{{ route('admin.products.create') }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg">
                        Add Product
                    </a>

                </div>
            @endforelse

        </div>

    </div>
@endsection
