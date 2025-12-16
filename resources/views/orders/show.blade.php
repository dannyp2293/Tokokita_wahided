<x-app-layout>
    <div class="max-w-5xl mx-auto py-10">

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-4">
            Detail Pesanan #{{ $order->id }}
        </h2>

        <p class="text-sm text-gray-600 mb-2">
            Status: <span class="font-semibold">{{ $order->status }}</span>
        </p>

        <p class="text-sm text-gray-600 mb-4">
            Tanggal: {{ $order->created_at }}
        </p>

        <div class="border rounded-lg divide-y">
            @foreach ($order->items as $item)
                <div class="flex justify-between items-center px-4 py-3">
                    <div>
                        <p class="font-medium">
                            {{ $item->product->nama ?? 'Produk dihapus' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Rp {{ number_format($item->price) }} x {{ $item->qty }}
                        </p>
                    </div>
                    <div class="font-semibold">
                        Rp {{ number_format($item->subtotal) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between font-bold text-lg mt-6 border-t pt-4">
            <span>Total</span>
            <span>Rp {{ number_format($order->total) }}</span>
        </div>

        <div class="mt-6">
            <a href="{{ route('products.index') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                Kembali ke Produk
            </a>
        </div>

    </div>
</x-app-layout>
