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
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">


                @foreach ($products as $product)
                    <div
                        class="bg-white border border-gray-200 shadow-md hover:shadow-xl transition duration-200 rounded-xl p-4 flex flex-col">

                        <!-- IMAGE -->
                        <div
                            class="w-full h-56 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">

                            @if ($product->images->count())
                                <div x-data='{
                                                images: @json($product->images->pluck('image')),
                                                index: 0,
                                                init(){
                                                setInterval(()=>{
                                                this.index = (this.index + 1) % this.images.length
                                                },3000)
                                                }
                                                }'
                                    class="relative w-full h-full flex items-center justify-center">

                                    <img :src="'/storage/' + images[index]"
                                        class="object-contain w-full h-full cursor-pointer"
                                        @click="open=true; image='/storage/' + images[index]">


                                    <!-- PREV -->
                                    <button x-show="images.length > 1"
                                        @click="index = (index - 1 + images.length) % images.length"
                                        class="absolute left-2 bg-black bg-opacity-40 text-white px-2 py-1 rounded">

                                        ‹

                                    </button>


                                    <!-- NEXT -->
                                    <button x-show="images.length > 1" @click="index = (index + 1) % images.length"
                                        class="absolute right-2 bg-black bg-opacity-40 text-white px-2 py-1 rounded">

                                        ›

                                    </button>

                                </div>
                            @else
                                <span class="text-gray-400 text-sm">
                                    No Image
                                </span>
                            @endif

                        </div>



                        <!-- NAME PRICE STOCK -->
                        <div class="my-3 text-center">

                            <p class="text-lg font-medium text-gray-800">
                                {{ $product->nama }}
                            </p>

                            <p class="text-gray-600 font-semibold">
                                Rp.{{ number_format($product->harga) }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Stok: {{ $product->stock }}
                            </p>

                        </div>



                       

                            <!-- USER -->
                            @auth
                                @cannot('update', $product)
                                    @if ($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST">

                                            @csrf

                                            <button
                                                class="bg-green-500 hover:bg-green-600 text-white py-2 w-full rounded-lg font-medium shadow">

                                                🛒 Add to Cart

                                            </button>

                                        </form>
                                    @else
                                        <button
                                            class="bg-gray-400 cursor-not-allowed text-white py-2 w-full rounded-lg font-medium shadow">

                                            Stok Habis

                                        </button>
                                    @endif
                                @endcannot
                            @endauth

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