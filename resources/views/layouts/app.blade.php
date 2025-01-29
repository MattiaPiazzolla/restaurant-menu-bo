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
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16">
                    <div class="w-full flex justify-between items-center">
                        <a href="{{ url('/') }}" class="font-semibold no-underline">
                            <img src="{{ asset('./storage/La-loggia-completo.svg') }}" class="w-[150px]" alt="">
                        </a>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                            <a href="{{ route('menus.index') }}" 
                               class="nav-link inline-flex items-center px-1 pt-1 text-sm text-gray-900 hover:text-gray-800 transition-colors duration-300">
                                <span class="menuBtn">Menu</span>
                            </a>
                            <a href="{{ route('menus.create') }}" 
                               class="nav-link inline-flex items-center py-2 px-4 text-sm rounded-full addDishBtn">
                                Aggiungi Piatto <i class="ml-2 fas fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" class="mobile-menu-button hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <a href="{{ route('menus.index') }}" 
                       class="no-underline block pl-3 pr-4 py-2 text-xl rounded-full font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-300">
                        Menu
                    </a>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="mobile-menu hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('menus.index') }}" 
                       class="no-underline block pl-3 pr-4 py-2 text-xl font-bold text-gray-900 hover:bg-gray-100 hover:text-gray-800 transition-colors duration-300">
                        Menu
                    </a>
                    <a href="{{ route('menus.create') }}" 
                       class="no-underline block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800 transition-colors duration-300">
                        Aggiungi Piatto <i class="ml-2 fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
            });

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

