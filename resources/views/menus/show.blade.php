@extends('layouts.app')

@section('content')


<style>
    [x-cloak] { display: none !important; }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        5% { opacity: 1; }
        95% { opacity: 1; }
        100% { opacity: 0; }
    }
    @keyframes slideOut {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>

<div class=" bg-gray-100" x-data="{ showDeleteModal: false, menuId: {{ $menu->id }} }">
    @if (session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => { show = false }, 5000)"
         x-show="show"
         x-transition.opacity.duration.300ms
         @click="show = false"
         class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md cursor-pointer"
         role="alert"
         aria-label="Success message. Click to dismiss">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg relative hover:bg-green-50 transition-colors duration-150">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
            <div class="mt-2" x-show="show">
                <div class="slider" style="width: 100%; height: 2px; background-color: #e2e8f0;">
                    <div class="slider-fill" style="width: 100%; height: 100%; background-color: #48bb78; animation: slideOut 5s linear forwards;"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
 
    <!-- Hero Section -->
    <div class="relative h-[50vh] bg-gray-900">
        @if($menu->image)
            <img src="{{ asset('storage/' . $menu->image) }}" 
                 alt="{{ $menu->name }}" 
                 class="w-full h-full object-cover opacity-80">
        @else
            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600"></div>
        @endif
        <div class="absolute inset-0 hero-gradient"></div>

        <!-- Floating Header -->
        <div class="absolute bottom-20 left-0 right-0 p-8">
            <div class="max-w-4xl mx-auto">
                <a href="{{ route('menus.index') }}" 
                   class="inline-flex items-center text-lg font-bold text-white hover:text-gray-200 mb-4 transition-colors duration-300 group">
                    <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:-translate-x-1" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                    Menu
                </a>
                <h1 class="text-5xl font-bold text-white tracking-tight">{{ $menu->name }}</h1>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="min-h-[52vh] max-w-4xl mx-auto px-4  -mt-24 relative z-10">
        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="frosted-glass rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 hover:bg-white hover:shadow-3xl">
            <!-- Details Grid -->
            <div class="p-8 space-y-8">
                <!-- Price and Category Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <p class="text-4xl font-bold text-gray-900">
                            €{{ number_format($menu->price, 2) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Prezzo include IVA</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ match($menu->category) {
                                'antipasto' => 'bg-green-100 text-green-800',
                                'pizza' => 'bg-orange-100 text-orange-800',
                                'primo' => 'bg-blue-100 text-blue-800',
                                'secondo' => 'bg-red-100 text-red-800',
                                'contorno' => 'bg-yellow-100 text-yellow-800',
                                'dolce' => 'bg-pink-100 text-pink-800',
                                'bevande' => 'bg-purple-100 text-purple-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                            {{ ucfirst($menu->category) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $menu->is_available 
                                ? 'bg-green-100 text-green-800' 
                                : 'bg-red-100 text-red-800' }}">
                            {{ $menu->is_available ? 'Disponibile' : 'Non disponibile' }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 leading-relaxed">
                        {{ $menu->description ?? 'Nessuna descrizione disponibile' }}
                    </p>
                </div>

                <!-- Tags -->
                @if($menu->tags->isNotEmpty())
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($menu->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm 
                                           bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors duration-300">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6  flex justify-end space-x-4">
                <a href="{{ route('menus.edit', $menu->id) }}" 
                    class="text-gray-600 p-3 rounded-full border-1 border-yellow-300 hover:bg-yellow-100 hover:text-yellow-600 transition duration-300 ease-in-out transform hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <button type="button" @click="showDeleteModal = true"
                class="text-gray-600 p-3 rounded-full border-1 border-red-300 hover:bg-red-100 hover:text-red-600 transition duration-300 ease-in-out transform hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-cloak
         x-show="showDeleteModal" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Elimina Piatto
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Sei sicuro di voler eliminare questo piatto? Questa azione non può essere annullata.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form :action="'/menus/' + menuId" method="POST" class="inline-block w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto">
                                Elimina
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Annulla
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection