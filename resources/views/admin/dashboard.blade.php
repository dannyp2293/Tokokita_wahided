@extends('layouts.admin')

@section('content')

<div class="py-10">
<div class="max-w-7xl mx-auto">

<h1 class="text-2xl font-bold mb-6">
Admin Dashboard
</h1>

<div class="grid grid-cols-4 gap-6">

<div class="bg-white p-6 rounded shadow">
<h2 class="text-gray-500">Total Users</h2>
<p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
</div>

<div class="bg-white p-6 rounded shadow">
<h2 class="text-gray-500">Total Orders</h2>
<p class="text-2xl font-bold">{{ \App\Models\Order::count() }}</p>
</div>

<div class="bg-white p-6 rounded shadow">
<h2 class="text-gray-500">Total Products</h2>
<p class="text-2xl font-bold">{{ \App\Models\Product::count() }}</p>
</div>

<div class="bg-white p-6 rounded shadow">
<h2 class="text-gray-500">Total Revenue</h2>
<p class="text-2xl font-bold">
Rp {{ number_format(\App\Models\Order::sum('total')) }}
</p>
</div>

</div>

<div class="bg-white p-6 rounded shadow mt-8">

<h2 class="text-lg font-semibold mb-4">
Recent Orders
</h2>

@foreach(\App\Models\Order::latest()->take(5)->get() as $order)

<div class="flex justify-between border-b py-2">

<div>
Order #{{ $order->id }}
</div>

<div>
Rp {{ number_format($order->total) }}
</div>

</div>

@endforeach

</div>

</div>
</div>

@endsection