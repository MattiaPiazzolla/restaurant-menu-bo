@extends('layouts.app')
@section('content')

<div class=" bg-gradient-to-b from-gray-50 to-white">
    
    <div class="max-w-5xl mx-auto px-6 py-24 sm:py-32">
        <!-- Hero Section -->
        <div class="text-center mb-24">
            <h1 class="text-6xl sm:text-7xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight mb-8">
                Gestione Ristorante
            </h1>
            <p class="text-xl text-gray-500 leading-relaxed max-w-2xl mx-auto font-light">
                Un modo semplice e intuitivo per gestire il menu e le prenotazioni del tuo ristorante.
            </p>
        </div>

        <!-- Feature Cards -->
        <div class="grid sm:grid-cols-3 gap-8 mb-24">
            <!-- Menu Card -->
            <div class="group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl transform transition-transform duration-500 group-hover:scale-105"></div>
                <div class="relative p-10 backdrop-blur-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Menu Digitale</h2>
                    <p class="text-gray-600 mb-8 font-light">
                        Gestisci il tuo menu in modo digitale, aggiorna prezzi e disponibilità in tempo reale.
                    </p>
                    <a href="{{ route('menus.index') }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
                        Inizia ora
                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Reservations Card -->
            <div class="group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-3xl transform transition-transform duration-500 group-hover:scale-105"></div>
                <div class="relative p-10 backdrop-blur-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Prenotazioni</h2>
                    <p class="text-gray-600 mb-8 font-light">
                        Gestisci le prenotazioni dei tavoli, visualizza il calendario e organizza il servizio.
                    </p>
                    <a href="{{ route('reservations.index') }}" class="inline-flex items-center text-amber-600 font-medium hover:text-amber-700">
                        Gestisci ora
                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- New Dishes Card -->
            <div class="group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl transform transition-transform duration-500 group-hover:scale-105"></div>
                <div class="relative p-10 backdrop-blur-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Nuovi Piatti</h2>
                    <p class="text-gray-600 mb-8 font-light">
                        Aggiungi nuovi piatti al tuo menu con foto, descrizioni e prezzi.
                    </p>
                    <a href="{{ route('menus.create') }}" class="inline-flex items-center text-purple-600 font-medium hover:text-purple-700">
                        Aggiungi piatto
                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

       <!-- Footer -->
<div class="mt-32 text-center">
    <div class="relative inline-flex items-center justify-center w-16 h-4">
        <span class="dot w-3 h-3 bg-blue-500 rounded-full"></span>
        <span class="dot w-3 h-3 bg-amber-500 rounded-full"></span>
        <span class="dot w-3 h-3 bg-purple-500 rounded-full"></span>
    </div>
</div>

<style>
@keyframes swapPositions {
    0% { transform: translateX(0); }
    33% { transform: translateX(16px); } /* Move right */
    66% { transform: translateX(-16px); } /* Move left */
    100% { transform: translateX(0); } /* Back to original */
}

.dot {
    position: absolute;
    animation: swapPositions 2s infinite ease-in-out;
}

.dot:nth-child(1) { animation-delay: 0s; }
.dot:nth-child(2) { animation-delay: 0.66s; }
.dot:nth-child(3) { animation-delay: 1.33s; }
</style>
    </div>
</div>

@endsection

