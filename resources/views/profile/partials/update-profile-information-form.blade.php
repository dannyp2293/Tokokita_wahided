<section class="bg-white shadow rounded-xl p-6">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            👤 Profile Information
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Update your account information and email address.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Name
            </label>

            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                required
                autofocus
            >

            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                required
            >

            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            {{-- Email Verification --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                <div class="mt-3 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                    Your email address is unverified.

                    <button form="send-verification"
                        class="ml-2 text-blue-600 hover:underline font-medium">
                        Resend verification email
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="text-sm text-green-600 mt-2">
                        Verification link sent!
                    </p>
                @endif

            @endif
        </div>

        {{-- Button --}}
        <div class="flex items-center gap-3 pt-2">
            <button
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >
                    Profile updated ✔
                </p>
            @endif
        </div>

    </form>

</section>