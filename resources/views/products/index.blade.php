<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-3">

        @if (session()->has('success'))
            <x-alert message="{{ session('success') }}" />
        @endif

        {{-- Header --}}
        <div class="flex mt-10 justify-between items-center">

            <h2 class="font-semibold text-2xl tracking-wide text-gray-700">
                List Products
            </h2>

            <div class="flex items-center space-x-4">

                {{-- Search Bar --}}
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="border border-gray-300 focus:ring focus:ring-blue-300 px-10 py-2 rounded-lg w-72 shadow-sm"
                    >

                    <span class="absolute left-3 top-2.5 text-gray-500">
                        🔍
                    </span>
                </form>

                {{-- Add Button --}}
                <a href="{{ route('products.create') }}">
                    <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-semibold flex items-center space-x-2 shadow">
                        <span>➕</span>
                        <span>Add</span>
                    </button>
                </a>

            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">

            @foreach ($products as $product)
                <div class="bg-white border border-gray-200 shadow-md hover:shadow-xl transition duration-200 rounded-xl p-4">

                    {{-- Image --}}
                    <div class="w-full h-56 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        @if ($product->foto)
                            <img src="{{ url('storage/' . $product->foto) }}" class="object-contain w-full h-full">
                        @else
                            <span class="text-gray-400 text-sm">No Image</span>
                        @endif
                    </div>

                    {{-- Name + Price --}}
                    <div class="my-3 text-center">
                        <p class="text-lg font-medium text-gray-800">{{ $product->nama }}</p>
                        <p class="text-gray-600 font-semibold">
                            Rp.{{ number_format($product->harga) }}
                        </p>
                    </div>

                    {{-- Edit Button --}}
                    <a href="{{ route('products.edit', $product) }}">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 w-full rounded-lg font-medium shadow">
                            Edit
                        </button>
                    </a>

                    {{-- Delete Button --}}
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            onclick="return confirm('Yakin mau hapus produk ini?')"
                            class="bg-red-500 hover:bg-red-600 text-white py-2 w-full rounded-lg font-medium shadow">
                            Delete
                        </button>
                    </form>

                </div>
            @endforeach

        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>
</x-app-layout>
