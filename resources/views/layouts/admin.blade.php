<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-gray-900 text-white p-6">

            <h2 class="text-xl font-bold mb-6">Admin Panel</h2>

            <nav class="space-y-4">

                <a href="{{ route('admin.dashboard') }}" class="block hover:text-blue-400">
                    Dashboard
                </a>

              <a href="{{ route('admin.products.index') }}"
                    class="block {{ request()->routeIs('admin.products.*') ? 'text-blue-400 font-bold' : 'hover:text-blue-400' }}">
                    Products
                    </a>

                <a href="{{ route('orders.index') }}" class="block hover:text-blue-400">
                    Orders
                </a>

                <a href="#" class="block hover:text-blue-400">
                    Users
                </a>

                <a href="#" class="block hover:text-blue-400">
                    Reports
                </a>

                <a href="#" class="block hover:text-blue-400">
                    Settings
                </a>

            </nav>

        </aside>

        <!-- CONTENT -->
        <main class="flex-1 p-10">

            @yield('content')

        </main>

    </div>

</body>

</html>
