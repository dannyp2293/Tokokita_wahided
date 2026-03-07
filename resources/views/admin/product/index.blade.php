@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

<div class="flex justify-between items-center mb-6">

<h1 class="text-2xl font-bold">
Products Management
</h1>

<a href="{{ route('admin.products.create') }}"
class="bg-green-500 text-white px-4 py-2 rounded">
+ Add Product
</a>

</div>

<div class="bg-white shadow rounded">

<table class="w-full text-left">

<thead class="border-b bg-gray-50">

<tr>
<th class="p-3">Image</th>
<th class="p-3">Name</th>
<th class="p-3">Price</th>
<th class="p-3">Stock</th>
<th class="p-3">Action</th>
</tr>

</thead>

<tbody>

@foreach($products as $product)

<tr class="border-b">

<td class="p-3">
<img src="{{ asset('storage/'.$product->image) }}"
class="w-16 rounded">
</td>

<td class="p-3">
{{ $product->nama }}
</td>

<td class="p-3">
Rp {{ number_format($product->harga) }}
</td>

<td class="p-3">
{{ $product->stock }}
</td>

<td class="p-3 flex gap-2">

<a href="{{ route('admin.products.edit',$product->id) }}"
class="bg-blue-500 text-white px-3 py-1 rounded">
Edit
</a>

<form method="POST"
action="{{ route('admin.products.destroy',$product->id) }}">

@csrf
@method('DELETE')

<button class="bg-red-500 text-white px-3 py-1 rounded">
Delete
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection