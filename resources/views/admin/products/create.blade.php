<x-app-layout>

<div class="max-w-5xl mx-auto px-6 py-6">

<h2 class="text-xl font-semibold mb-6">Create Product</h2>

<div x-data="{ imageUrl: '/storage/noimage.png' }">

<form enctype="multipart/form-data"
      method="POST"
      action="{{ route('admin.products.store') }}"
      class="grid grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow">

@csrf

<!-- IMAGE PREVIEW -->
<div>
    <img :src="imageUrl"
         class="rounded-md w-full h-80 object-cover border">
</div>

<!-- FORM INPUT -->
<div>

<div class="mb-4">
<x-input-label value="Images" />

<input type="file"
       name="images[]"
       accept="image/*"
       @change="imageUrl = URL.createObjectURL($event.target.files[0])"
       class="block w-full border p-2 rounded">

</div>

<div class="mb-4">
<x-input-label value="Nama"/>
<x-text-input name="nama" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="Harga"/>
<x-text-input name="harga" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="Stock"/>
<x-text-input name="stock" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="Deskripsi"/>
<textarea name="deskripsi" class="w-full border rounded p-2"></textarea>
</div>

<button class="w-full bg-gray-800 text-white py-2 rounded">
Submit
</button>

</div>

</form>
</div>

</div>
</div>

</x-app-layout>