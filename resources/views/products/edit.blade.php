<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-3">

        <div class="flex mt-6">
            <h2 class="font-semibold text-xl">Edit Product</h2>
        </div>

        @php
            $firstImage = $product->images->first();
        @endphp

        <div class="mt-4">

            <form enctype="multipart/form-data" method="POST" action="{{ route('products.update', $product) }}"
                class="flex gap-6">

                @csrf
                @method('PUT')

                {{-- LEFT SIDE IMAGE --}}
                <div class="w-1/2">

                    <div x-data="{ image: '{{ $firstImage ? asset('storage/' . $firstImage->image) : '' }}' }">

                        {{-- MAIN IMAGE --}}
                       <img :src="image" class="rounded-md mb-4 w-full h-auto object-contain border">

                        {{-- THUMBNAIL --}}
                        <div class="flex gap-3 mt-3 flex-wrap">

                            @foreach ($product->images as $img)
                                <div class="relative group">
                                    <img
                                        src="{{ asset('storage/'.$img->image) }}"
                                        class="w-20 h-20 object-cover cursor-pointer border rounded hover:scale-105 transition"
                                        @click="image='{{ asset('storage/'.$img->image) }}'"
                                    >
                                    <button
                                        onclick="event.preventDefault(); document.getElementById('delete-img-{{ $img->id }}').submit();"
                                        class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100">
                                        ✕
                                    </button>
                                </div>
                                <!-- Form delete dipindahkan ke luar form utama, jadi di sini tidak ada form -->
                            @endforeach

                        </div>

                    </div>

                    {{-- UPLOAD NEW IMAGE --}}
                    <div class="mt-4">

                        <x-input-label value="Tambah Gambar Baru" />

                        <input type="file" name="images[]" multiple accept="image/*"
                            class="block w-full border p-2 rounded">

                    </div>

                </div>

                {{-- RIGHT SIDE FORM --}}
                <div class="w-1/2">

                    <div class="mt-4">
                        <x-input-label for="nama" :value="__('Nama')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                            :value="old('nama', $product->nama)" required />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="harga" :value="__('Harga')" />
                        <x-text-input id="harga" class="block mt-1 w-full" type="number" name="harga"
                            :value="old('harga', $product->harga)" required />
                        <x-input-error :messages="$errors->get('harga')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="stock" :value="__('Stock')" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock"
                            :value="old('stock', $product->stock)" required />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <x-text-area id="deskripsi" class="block mt-1 w-full" name="deskripsi">
                            {{ old('deskripsi', $product->deskripsi) }}
                        </x-text-area>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full mt-6 bg-gray-800 text-white py-3 rounded-lg hover:bg-gray-700 transition">
                        Submit
                    </button>

                </div>

            </form>

            {{-- SEMUA FORM DELETE DIPINDAHKAN KE SINI, DI LUAR FORM UTAMA --}}
            @foreach ($product->images as $img)
                <form id="delete-img-{{ $img->id }}" method="POST"
                      action="{{ route('products.image.delete', $img->id) }}">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

        </div>

    </div>

</x-app-layout>