<x-app-layout>

  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Account Settings
    </h2>
</x-slot>

    

    <div class="py-10">
        <div class="max-w-4xl mx-auto space-y-6">

            <div class="bg-white shadow rounded-xl p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-red-50 border border-red-200 shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-red-700 mb-4">
                    ⚠ Danger Zone
                </h3>

                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

</x-app-layout>