<!doctype
html >
  <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Loggia Gestionale</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('./storage/la-loggia-icon.svg') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    
    <!-- Initialize Alpine.js store -->
    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine.store('menuState', {
                currentMenuId: null,
                setCurrentMenu(id) {
                    this.currentMenuId = id;
                }
            });
        });
    </script>
    <style>
        /* Desktop Navigation Styles */
        .nav-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .nav-link {
            padding: 0.75rem 1.25rem; /* py-3 px-5 */
            z-index: 10;
            transition: color 0.3s ease;
            position: relative;
            color: #000000;
        }
        
        .nav-pill {
            position: absolute;
            height: 38px;
            border-radius: 24px;
            background: rgba(79, 70, 229, 0.08);
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 0;
        }
        
        .nav-link.active {
            color: #000000;
        }
        
        /* Mobile Navigation Styles */
        .mobile-nav-container {
            position: relative;
        }
        
        .mobile-nav-pill {
            position: absolute;
            height: 42px;
            width: 42px;
            border-radius: 21px;
            background: rgba(79, 70, 229, 0.08);
            z-index: 0;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hidden-pill {
            width: 0px !important;
            height: 0px !important;
            opacity: 0;
            overflow: hidden;
        }
    </style>
</head>
<body class="antialiased">
    <div id="app" class="min-h-screen flex flex-col">
        <nav 
            x-data="{
                desktopPillPosition: 0,
                desktopPillWidth: 0,
                mobilePillPosition: 0,
                activeRoute: '{{ request()->route()->getName() }}',
                hasActiveLink: false,
                isWelcomePage: {{ json_encode(request()->routeIs('welcome')) }},
                currentUrl: '',
                currentMenuId: null,
                isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
                
                isMainRoute() {
                    const restrictedRoutes = ['menus', 'reservations', 'schedules'];
                    return restrictedRoutes.some(route => this.activeRoute?.startsWith(route));
                },

                shouldShowPill() {
                    const pillRoutes = ['menus.index', 'reservations.index', 'schedules.index'];
                    return pillRoutes.includes(this.activeRoute);
                },
                
                // Add this new method
                handleWelcomeClick(event) {
                    event.preventDefault();
                    this.currentUrl = event.currentTarget.href;
                    this.activeRoute = 'welcome';
                    this.isWelcomePage = true;
                    this.hasActiveLink = false;
                    
                    fetch(this.currentUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('main');
                        if (newContent) {
                            document.querySelector('main').innerHTML = newContent.innerHTML;
                        }
                        window.history.pushState({}, '', this.currentUrl);
                    });
                },
                
                updatePillPositions() {
                    this.$nextTick(() => {
                        const desktopActiveLink = document.querySelector('.nav-container .nav-link.active');
                        if (desktopActiveLink) {
                            const padding = 10; // Pixels to extend pill on each side (adjust as needed)
                            this.desktopPillPosition = desktopActiveLink.offsetLeft - padding;
                            this.desktopPillWidth = desktopActiveLink.offsetWidth + 2 * padding;
                            this.hasActiveLink = true;
                        } else {
                            this.desktopPillWidth = 0;
                            this.hasActiveLink = false;
                        }
                        
                        const mobileActiveLink = document.querySelector('.mobile-nav-container .active');
                        if (mobileActiveLink) {
                            this.mobilePillPosition = mobileActiveLink.offsetLeft + (mobileActiveLink.offsetWidth / 2) - 21;
                        }
                    });
                },
                
                navigateTo(event, route) {
                    if (!event || !event.currentTarget) {
                        console.error('Invalid event object');
                        return;
                    }

                    event.preventDefault();
                    this.currentUrl = event.currentTarget.href;
                    
                    if (this.activeRoute === route) return;
                    
                    this.activeRoute = route;
                    
                    // Update active classes for all navigation links
                    const allNavLinks = document.querySelectorAll('[data-route]');
                    allNavLinks.forEach(link => {
                        if (link.dataset.route === route) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                    
                    // Update pill positions
                    this.updatePillPositions();
                    
                    // Fetch and update content
                    fetch(this.currentUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.status === 401) {
                            window.location.href = '{{ route("login") }}';
                            throw new Error('Unauthorized');
                        }
                        return response.text();
                    })
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('main');
                        if (newContent) {
                            document.querySelector('main').innerHTML = newContent.innerHTML;
                        }
                        window.history.pushState({}, '', this.currentUrl);
                    })
                    .catch(error => {
                        if (error.message === 'Unauthorized') return;
                        console.error('Navigation error:', error);
                        if (this.currentUrl) {
                            window.location.href = this.currentUrl;
                        }
                    });
                },
                
                mobileNavigateTo(event, route) {
                    if (!event || !event.currentTarget) {
                        console.error('Invalid event object');
                        return;
                    }

                    event.preventDefault();
                    this.currentUrl = event.currentTarget.href;
                    
                    if (this.activeRoute === route) return;
                    
                    this.activeRoute = route;
                    
                    // Update active classes for all navigation links
                    const allNavLinks = document.querySelectorAll('[data-route]');
                    allNavLinks.forEach(link => {
                        if (link.dataset.route === route) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                    
                    // Update pill positions
                    this.updatePillPositions();
                    
                    // Fetch and update content
                    fetch(this.currentUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.status === 401) {
                            window.location.href = '{{ route("login") }}';
                            throw new Error('Unauthorized');
                        }
                        return response.text();
                    })
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('main');
                        if (newContent) {
                            document.querySelector('main').innerHTML = newContent.innerHTML;
                        }
                        window.history.pushState({}, '', this.currentUrl);
                    })
                    .catch(error => {
                        if (error.message === 'Unauthorized') return;
                        console.error('Navigation error:', error);
                        if (this.currentUrl) {
                            window.location.href = this.currentUrl;
                        }
                    });
                },
                
                init() {
                    this.updatePillPositions();
                    
                    window.addEventListener('resize', () => {
                        this.updatePillPositions();
                    });
                    
                    window.addEventListener('popstate', () => {
                        fetch(window.location.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('main');
                            if (newContent) {
                                document.querySelector('main').innerHTML = newContent.innerHTML;
                            }
                            
                            // Update active classes for all navigation links
                            const currentPath = window.location.pathname;
                            const allNavLinks = document.querySelectorAll('[data-route]');
                            allNavLinks.forEach(link => {
                                if (link.getAttribute('href') === currentPath) {
                                    link.classList.add('active');
                                } else {
                                    link.classList.remove('active');
                                }
                            });
                            
                            // Update activeRoute
                            const activeLink = Array.from(allNavLinks).find(link => link.classList.contains('active'));
                            this.activeRoute = activeLink ? activeLink.dataset.route : null;
                            
                            // Update pill positions
                            this.updatePillPositions();
                        });
                    });
                },

                getPillWidth() {
                    if (!this.isAuthenticated && this.isMainRoute()) {
                        return '100%'; // Full width when logged out
                    }
                    return `${this.desktopPillWidth}px`; // Normal width when logged in
                },

                getPillClasses() {
                    return {
                        'mobile-nav-pill': true,
                        'hidden-pill': !this.isMainRoute()
                    };
                },
            }" 
            x-init="init()"
            class="bg-white shadow-sm border-b border-gray-100"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo Section -->
                    <div class="flex justify-between w-full sm:w-auto items-center">
                        <a href="{{ route('welcome') }}" 
                           class="font-semibold"
                           @click="handleWelcomeClick($event)">
                            <img src="{{ asset('./storage/la-loggia-icon.svg') }}" class="w-[75px] sm:hidden brightness-0" alt="La Loggia Icon">
                            <img src="{{ asset('./storage/La-loggia-completo.svg') }}" class="w-[150px] hidden sm:block" alt="La Loggia Logo">
                        </a>
                        
                        <!-- Mobile Navigation -->
                        <div class="sm:hidden flex items-center space-x-8 mobile-nav-container">
                            <!-- Mobile Pill Indicator -->
                            <div :class="getPillClasses()"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 x-bind:style="isMainRoute() ? `transform: translateX(${mobilePillPosition}px);` : ''">
                            </div>
                            
                            <a href="{{ route('menus.index') }}" 
                               class="p-2 z-10 relative {{ request()->routeIs('menus.*') ? 'active text-black' : 'text-black' }}"
                               data-route="menus.index"
                               @click="mobileNavigateTo($event, 'menus.index')">
                                <i class="fas fa-utensils text-xl"></i>
                            </a>
                            <a href="{{ route('reservations.index') }}" 
                               class="p-2 z-10 relative {{ request()->routeIs('reservations.*') ? 'active text-black' : 'text-black' }}"
                               data-route="reservations.index"
                               @click="mobileNavigateTo($event, 'reservations.index')">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </a>
                            <a href="{{ route('schedules.index') }}" 
                               class="p-2 z-10 relative {{ request()->routeIs('schedules.*') ? 'active text-black' : 'text-black' }}"
                               data-route="schedules.index"
                               @click="mobileNavigateTo($event, 'schedules.index')">
                                <i class="fas fa-clock text-xl"></i>
                            </a>
                            @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="p-2 z-10 relative text-red-600">
                                        <i class="fas fa-sign-out-alt text-xl"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="p-2 z-10 relative text-indigo-600">
                                    <i class="fas fa-sign-in-alt text-xl"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 sm:space-x-6 nav-container">
                        <!-- Desktop Pill Indicator -->
                        <div :class="getPillClasses()"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             x-bind:style="isMainRoute() ? `transform: translateX(${desktopPillPosition}px); width: ${!isAuthenticated ? '100%' : desktopPillWidth + 'px'};` : ''">
                        </div>
                        
                        <a href="{{ route('menus.index') }}" 
                           class="nav-link flex items-center {{ request()->routeIs('menus.*') ? 'active' : '' }}"
                           data-route="menus.index"
                           @click="navigateTo($event, 'menus.index')">
                            <i class="fas fa-utensils"></i>
                            <span class="ml-2">Menu</span>
                        </a>
                        <a href="{{ route('reservations.index') }}" 
                           class="nav-link flex items-center {{ request()->routeIs('reservations.*') ? 'active' : '' }}"
                           data-route="reservations.index"
                           @click="navigateTo($event, 'reservations.index')">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="ml-2">Prenotazioni</span>
                        </a>
                        <a href="{{ route('schedules.index') }}" 
                           class="nav-link flex items-center {{ request()->routeIs('schedules.*') ? 'active' : '' }}"
                           data-route="schedules.index"
                           @click="navigateTo($event, 'schedules.index')">
                            <i class="fas fa-clock"></i>
                            <span class="ml-2">Orari</span>
                        </a>
                        @auth
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button type="submit" class="nav-link flex items-center text-red-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="ml-2">Esci</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="nav-link flex items-center text-indigo-600">
                                <i class="fas fa-sign-in-alt"></i>
                                <span class="ml-2">Accedi</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="flex-grow">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
    </div>
    
    <!-- Smooth Scroll for Anchor Links -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

