<x-app-layout>
    <div class="max-w-5xl mx-auto py-10">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Riwayat Pesanan Saya</h2>
            <a href="{{ route('products.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Belanja Lagi
            </a>
        </div>

        @forelse ($orders as $order)
            <div class="border rounded-lg p-4 mb-4 shadow-sm hover:shadow-md transition">

                {{-- Header Order --}}
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <span class="font-bold text-blue-600">Pesanan #{{ $order->id }}</span>
                        <span class="text-sm text-gray-500 ml-3">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'paid') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $order->status }}
                    </span>
                </div>

                {{-- Items Preview (ambil 2 item pertama) --}}
                <div class="space-y-2">
                    @foreach($order->items->take(2) as $item)
                        <div class="flex justify-between text-sm">
                            <span>
                                {{ $item->product->nama ?? 'Produk' }}
                                <span class="text-gray-500">x{{ $item->qty }}</span>
                            </span>
                            <span>Rp {{ number_format($item->subtotal) }}</span>
                        </div>
                    @endforeach

                    @if($order->items->count() > 2)
                        <div class="text-sm text-gray-500">
                            + {{ $order->items->count() - 2 }} produk lainnya
                        </div>
                    @endif
                </div>

                {{-- Total & Detail Link --}}
                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                    <span class="font-semibold">Total</span>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-blue-600">
                            Rp {{ number_format($order->total) }}
                        </span>
                        <a href="{{ route('orders.show', $order) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail →
                        </a>
                    </div>
                </div>

            </div>
        @empty
            {{-- Kosong --}}
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">
                    Belum Ada Pesanan
                </h3>
                <p class="text-gray-500 mb-6">
                    Yuk, belanja sekarang juga!
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    Mulai Belanja
                </a>
            </div>
        @endforelse

    </div>
</x-app-layout>
