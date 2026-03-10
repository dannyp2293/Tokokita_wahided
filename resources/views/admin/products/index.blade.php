@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-10">

<div class="flex justify-between items-center mb-8">

<h1 class="text-2xl font-bold text-gray-700">
Products Management
</h1>

<a href="{{ route('admin.products.create') }}"
class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">
+ Add Product
</a>

</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

@forelse($products as $product)

<div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4">

{{-- IMAGE --}}
@if($product->images->first())

<img src="{{ asset('storage/'.$product->images->first()->image) }}"
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
@if($product->stock > 0)

<span class="inline-block mt-2 bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
{{ $product->stock }} available
</span>

@else

<span class="inline-block mt-2 bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
Out of Stock
</span>

@endif


{{-- BUTTON --}}
<div class="flex gap-2 mt-4">

<a href="{{ route('admin.products.edit',$product->id) }}"
class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white py-1 rounded text-sm">
Edit
</a>

<form method="POST"
action="{{ route('admin.products.destroy',$product->id) }}"
class="flex-1"
onsubmit="return confirm('Delete this product?')">

@csrf
@method('DELETE')

<button class="w-full bg-red-500 hover:bg-red-600 text-white py-1 rounded text-sm">
Delete
</button>

</form>

</div>

</div>

@empty

<p class="text-gray-400">No products found</p>

@endforelse

</div>

</div>

@endsection