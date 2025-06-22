<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QR Offer Admin') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.offers.index') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-800 {{ request()->routeIs('offers.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Offers
                </a>                
                <a href="{{ route('admin.offer.claims.index') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-800 {{ request()->routeIs('offer.claims.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Offer Claims
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 rounded hover:bg-red-100 text-red-600 font-medium">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Page Header (optional) -->
        @hasSection('header')
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>
@yield('scripts')
</body>
</html>
