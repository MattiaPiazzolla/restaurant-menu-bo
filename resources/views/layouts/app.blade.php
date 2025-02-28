<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Loggia Gestionale</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('./storage/la-loggia-icon.svg') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased">
    <div id="app" class="min-h-screen flex flex-col">
        <nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex items-center">
                            <a href="{{ url('/') }}" class="font-semibold no-underline">
                                <img src="{{ asset('./storage/La-loggia-completo.svg') }}" class="w-[150px]" alt="">
                            </a>
                        </div>
                    </div>
                    
                    <!-- Desktop navigation -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 sm:space-x-8">
                        <a href="{{ route('menus.index') }}" 
                           class="nav-link inline-flex items-center px-1 pt-1 text-sm text-gray-900 hover:text-gray-800 transition-colors duration-300">
                            <span class="menuBtn">Menu</span>
                        </a>
                        <a href="{{ route('menus.create') }}" 
                           class="nav-link inline-flex items-center py-1 px-4 text-sm rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300 addDishBtn">
                            Aggiungi Piatto <i class="ml-2 fas fa-plus"></i>
                        </a>
                        
                        <a href="{{ route('reservations.index') }}" 
                           class="nav-link inline-flex items-center py-2 px-4 text-sm rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                            {{ __('Prenotazioni') }} <i class="ml-2 fas fa-calendar-alt"></i>
                        </a>

                        <a href="{{ route('restaurant-status.index') }}" 
                           class="nav-link inline-flex items-center py-2 px-4 text-sm rounded-full bg-green-600 text-white hover:bg-green-700 transition-colors duration-300">
                            {{ __('Stato Ristorante') }} <i class="ml-2 fas fa-store"></i>
                        </a>
                        
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link inline-flex items-center py-2 px-4 text-sm rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors duration-300">
                                    Logout <i class="ml-2 fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="nav-link inline-flex items-center py-2 px-4 text-sm rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                                Login <i class="ml-2 fas fa-sign-in-alt"></i>
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile icons navbar -->
                    <div class="flex items-center sm:hidden space-x-3">
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-red-600 text-white hover:bg-red-700 transition-colors duration-300">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        @endauth
                        
                        <a href="{{ route('menus.index') }}" 
                           class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                            <i class="fas fa-utensils"></i>
                        </a>
                        
                        <a href="{{ route('menus.create') }}" 
                           class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                            <i class="fas fa-plus"></i>
                        </a>

                        <a href="{{ route('reservations.index') }}" 
                           class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                            <i class="fas fa-calendar-alt"></i>
                        </a>

                        <a href="{{ route('restaurant-status.index') }}" 
                           class="no-underline inline-flex items-center px-3 py-2 text-sm rounded-full font-medium bg-green-600 text-white hover:bg-green-700 transition-colors duration-300">
                            <i class="fas fa-store"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>