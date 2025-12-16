<x-app-layout>
    <div class="max-w-5xl mx-auto py-10">

        <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>
@php $total = 0; @endphp

@forelse ($cart as $id => $item)
    @php
        $subtotal = $item['harga'] * $item['qty'];
        $total += $subtotal;
    @endphp

<div class="flex items-center border-b py-4">

    {{-- Product Info --}}
    <div class="w-1/3">
        <p class="font-medium">{{ $item['nama'] }}</p>
        <p class="text-sm text-gray-500">
            Rp {{ number_format($item['harga']) }}
        </p>
    </div>

    {{-- Qty Control --}}
    <div class="w-32 flex justify-center">
        <div class="flex items-center gap-2">
            <form action="{{ route('cart.decrease', $id) }}" method="POST">
                @csrf
                <button class="px-3 py-1 bg-gray-200 rounded">−</button>
            </form>

            <span class="w-6 text-center">{{ $item['qty'] }}</span>

            <form action="{{ route('cart.increase', $id) }}" method="POST">
                @csrf
                <button class="px-3 py-1 bg-gray-200 rounded">+</button>
            </form>
        </div>
    </div>

    {{-- Subtotal --}}
    <div class="w-1/4 text-right font-semibold">
        Rp {{ number_format($subtotal) }}
    </div>

    {{-- Remove --}}
    <div class="w-10 text-right">
        <form action="{{ route('cart.remove', $id) }}" method="POST">
            @csrf
            <button
                onclick="return confirm('Hapus item ini?')"
                class="text-red-500 hover:text-red-700 font-bold">
                ✕
            </button>
        </form>
    </div>

</div>

@empty
    <p class="text-gray-500">Keranjang masih kosong.</p>
@endforelse

{{-- Total & Tombol Checkout --}}
@if (count($cart))
    <div class="flex justify-between font-bold text-lg mt-6 border-t pt-4 mb-4">
        <span>Total</span>
        <span>Rp {{ number_format($total) }}</span>
    </div>

    <a href="{{ route('cart.checkout') }}"
       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow">
        Checkout
    </a>
@endif

    </div>
</x-app-layout>
