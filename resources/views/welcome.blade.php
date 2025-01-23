@extends('layouts.app')
@section('content')

<div class=" bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-5xl mx-auto px-6 py-24 sm:py-32">
        <!-- Hero Section -->
        <div class="text-center mb-24">
            <h1 class="text-6xl sm:text-7xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight mb-8">
                Gestione Menu
            </h1>
            <p class="text-xl text-gray-500 leading-relaxed max-w-2xl mx-auto font-light">
                Un modo semplice e intuitivo per gestire il menu del tuo ristorante.
            </p>
        </div>

        <!-- Feature Cards -->
        <div class="grid sm:grid-cols-2 gap-8 mb-24">
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

            <div class="group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl transform transition-transform duration-500 group-hover:scale-105"></div>
                <div class="relative p-10 backdrop-blur-sm">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Nuovi Piatti</h2>
                    <p class="text-gray-600 mb-8 font-light">
                        Aggiungi nuovi piatti al tuo menu con foto, descrizioni e prezzi.
                    </p>
                    <a href="{{ route('menus.create') }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
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
            <div class="inline-flex items-center justify-center space-x-2 mb-4">
                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                <span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse delay-100"></span>
                <span class="w-2 h-2 bg-pink-500 rounded-full animate-pulse delay-200"></span>
            </div>
        </div>
    </div>
</div>

@endsection

