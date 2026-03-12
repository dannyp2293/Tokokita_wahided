<x-app-layout>

    <div x-data="{ open: false, image: '' }">

        <!-- POPUP MODAL -->
        <div x-show="open" x-transition.scale
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.self="open=false">

            <div class="bg-white rounded-lg shadow-lg p-4 max-w-3xl w-full relative">

                <button @click="open=false" class="absolute top-2 right-3 text-gray-600 text-xl">
                    ✕
                </button>

                <img :src="image" class="w-full h-auto rounded-lg">

            </div>
        </div>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-3">

            @if (session()->has('success'))
                <x-alert message="{{ session('success') }}" />
            @endif


            <!-- HEADER -->
            <div class="flex mt-10 justify-between items-center">

                <h2 class="font-semibold text-2xl tracking-wide text-gray-700">
                    List Products
                </h2>

                <div class="flex items-center space-x-4">

                    <!-- SEARCH -->
                    <form action="{{ route('products.index') }}" method="GET" class="relative">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk..."
                            class="border border-gray-300 focus:ring focus:ring-blue-300 px-10 py-2 rounded-lg w-72 shadow-sm">

                        <span class="absolute left-3 top-2.5 text-gray-500">
                            🔍
                        </span>

                    </form>

                </div>

            </div>


            <!-- PRODUCT GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">

                @foreach ($products as $product)
                    <div
                        class="bg-white rounded-xl shadow hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">

                        <!-- IMAGE -->
                        <div class="relative bg-gray-100 h-52 flex items-center justify-center overflow-hidden group">

                            @if ($product->images->count())
                                <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                                    class="object-contain h-full transition duration-300 group-hover:scale-110">
                            @else
                                <span class="text-gray-400 text-sm">
                                    No Image
                                </span>
                            @endif


                            <!-- STOCK BADGE -->
                            @if ($product->stock > 0)
                                <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    In Stock
                                </span>
                            @else
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    Sold Out
                                </span>
                            @endif

                        </div>


                        <!-- INFO -->
                        <div class="p-4 flex flex-col flex-1">

                            <h3 class="text-gray-800 font-semibold text-sm line-clamp-2">
                                {{ $product->nama }}
                            </h3>

                            <p class="text-green-600 font-bold text-lg mt-1">
                                Rp {{ number_format($product->harga) }}
                            </p>

                            <p class="text-xs text-gray-400">
                                Stok: {{ $product->stock }}
                            </p>


                            <!-- BUTTON -->
                            <div class="mt-auto pt-3">

                                @auth

                                    @if ($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST">

                                            @csrf

                                            <button
                                                class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-medium transition">

                                                🛒 Add to Cart

                                            </button>

                                        </form>
                                    @else
                                        <button class="w-full bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed">

                                            Stok Habis

                                        </button>
                                    @endif

                                @endauth

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>


            <div class="mt-6">
                {{ $products->links() }}
            </div>


        </div>

    </div>

</x-app-layout>
