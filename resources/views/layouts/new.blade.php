<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pengaduan Masyarakat')</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('styles')
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    @if (request()->is('/'))
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="font-bold text-2xl text-blue-600">
                                Pengaduan Masyarakat
                            </a>
                        </div>
                    @else
                        <div class="flex justify-center items-center">
                            <a href="{{ url('/') }}"><img class="object-cover h-16 w-36 "
                                    src="{{ asset('assets/maskot.png') }}" alt=""></a>
                        </div>
                    @endif

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:ml-10 sm:flex">
                        <a href="{{ route('posts.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('posts.index') ? 'border-blue-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            Posts
                        </a>
                    </div>
                </div>

                <!-- Authentication -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ url('/admin/dashboard') }}"
                                    class="text-gray-500 hover:text-gray-700 px-3 py-2">Dashboard</a>
                            @elseif (auth()->user()->role === 'staff')
                                <a href="{{ url('/staff/dashboard') }}"
                                    class="text-gray-500 hover:text-gray-700 px-3 py-2">Dashboard</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="ml-4 text-gray-500 hover:text-gray-700 px-3 py-2">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Pengaduan Masyarakat. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @yield('scripts')

    {{-- Liking notification --}}
    {{-- @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if (session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
        {{ session('info') }}
    </div>
    @endif --}}
</body>

</html>