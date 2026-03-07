<section class="bg-white shadow rounded-xl p-6">
    
    <h2 class="text-xl font-semibold mb-4">
        🔒 Update Password
    </h2>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label class="block text-sm font-medium">Current Password</label>
            <input type="password" name="current_password"
                class="mt-1 w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium">New Password</label>
            <input type="password" name="password"
                class="mt-1 w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="mt-1 w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update Password
        </button>

    </form>

</section>