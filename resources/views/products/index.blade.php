<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-3">

        @if (session()->has('success'))
            <x-alert message="{{ session('success') }}" />
        @endif

        <div class="flex mt-6 justify-between">
            <h2 class="font-semibold text-xl">List Products</h2>
            <a href="{{ route('products.create') }}">
                <button class="bg-green-500 hover:bg-green-600 px-10 py-2 rounded-md font-semibold">
                    Add
                </button>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 mt-2">

            @foreach ($products as $product)
                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-4">

                    <img src="{{ url('storage/'.$product->foto) }}" />

                    <div class="my-2">
                        <p class="text-xl font-light">{{ $product->nama }}</p>
                        <p class="font-semibold text-gray-700">
                            Rp.{{ number_format($product->harga) }}
                        </p>
                    </div>

                    <a href="{{ route('products.edit', $product) }}">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-10 py-2 w-full rounded-md font-semibold">
                            Edit
                        </button>
                    </a>

                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="mt-2">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                onclick="return confirm('Yakin mau hapus produk ini?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-10 py-2 w-full rounded-md font-semibold">
                            Delete
                        </button>
                    </form>

                </div> {{-- ✔ PENUTUP CARD --}}
            @endforeach

        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</x-app-layout>
