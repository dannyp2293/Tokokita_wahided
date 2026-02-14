<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg mb-6">
                <h2 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h2>
                <p class="mt-2">Selamat datang kembali di tokokita</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Quick Actions --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <span class="bg-blue-100 p-2 rounded-lg mr-2">⚡</span>
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}"
                           class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            🛍️  Belanja Sekarang
                        </a>
                        <a href="{{ route('cart.index') }}"
                           class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            🛒  Lihat Keranjang
                            @if(session()->has('cart') && count(session('cart')) > 0)
                                <span class="float-right bg-red-500 text-white px-2 py-0.5 rounded-full text-xs">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}"
                           class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            📦  Cek Status Pesanan
                        </a>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <span class="bg-green-100 p-2 rounded-lg mr-2">📊</span>
                        Statistik Belanjamu
                    </h3>

                    @php
                        $orderCount = App\Models\Order::where('user_id', Auth::id())->count();
                        $totalSpent = App\Models\Order::where('user_id', Auth::id())->sum('total');
                    @endphp

                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Total Pesanan</span>
                            <span class="font-bold">{{ $orderCount }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Total Belanja</span>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($totalSpent) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Member Sejak</span>
                            <span class="font-bold">
                                {{ Auth::user()->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Recent Orders Preview --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-2">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <span class="bg-yellow-100 p-2 rounded-lg mr-2">🕒</span>
                        Pesanan Terakhir
                    </h3>

                    @php
                        $recentOrders = App\Models\Order::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp

                    @if($recentOrders->isEmpty())
                        <p class="text-gray-500 text-center py-4">
                            Belum ada pesanan.
                            <a href="{{ route('products.index') }}" class="text-blue-500 hover:underline">
                                Yuk belanja!
                            </a>
                        </p>
                    @else
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <div class="flex justify-between items-center border-b pb-2">
                                    <div>
                                        <span class="font-medium">Order #{{ $order->id }}</span>
                                        <span class="text-sm text-gray-500 ml-2">
                                            {{ $order->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="px-2 py-0.5 rounded text-xs
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'paid') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                        <a href="{{ route('orders.show', $order) }}"
                                           class="ml-3 text-blue-500 hover:underline text-sm">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-right">
                            <a href="{{ route('orders.index') }}" class="text-blue-500 hover:underline">
                                Lihat Semua Pesanan →
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
