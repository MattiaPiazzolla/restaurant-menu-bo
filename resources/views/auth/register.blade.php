@extends('layouts.app')

@section('title', '404 - Pagina Non Trovata')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100">
    <div class="max-w-md w-full px-6 py-8 bg-white rounded-xl shadow-lg text-center">
        <div class="relative mb-6">
            <!-- Pizza SVG illustration -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-40 h-40 mx-auto">
                <circle cx="256" cy="256" r="240" fill="#F9A825"/>
                <circle cx="256" cy="256" r="200" fill="#FFCC80"/>
                <!-- Pizza slice missing (404) -->
                <path d="M256 256 L456 256 A200 200 0 0 0 367 88 z" fill="#F9A825"/>
                <!-- Pepperonis -->
                <circle cx="180" cy="150" r="20" fill="#D32F2F"/>
                <circle cx="300" cy="190" r="20" fill="#D32F2F"/>
                <circle cx="220" cy="300" r="20" fill="#D32F2F"/>
                <circle cx="320" cy="320" r="20" fill="#D32F2F"/>
                <circle cx="150" cy="240" r="20" fill="#D32F2F"/>
                <circle cx="380" cy="150" r="15" fill="#D32F2F"/>
            </svg>
            <!-- Missing slice text -->
            <div class="absolute top-6 right-0 transform rotate-45 bg-red-600 text-white py-1 px-3 rounded-lg font-mono">
                404
            </div>
        </div>
        
        <h1 class="text-4xl font-bold text-red-600 mb-2">Mamma Mia!</h1>
        <h2 class="text-2xl font-semibold text-orange-800 mb-4">Pagina Non Trovata</h2>
        
        <p class="text-gray-700 mb-6">Sembra che qualcuno abbia mangiato la pagina che stavi cercando! I nostri fattorini non riescono a trovare la destinazione.</p>
        
        <div class="mb-8">
            <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                Errore 404
            </span>
        </div>
        
        <a href="/" class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md">
            <div class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Torna al Menù
            </div>
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    svg {
        animation: float 5s ease-in-out infinite;
    }
</style>
@endsection