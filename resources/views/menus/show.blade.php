@extends('layouts.app')

@section('content')
<!-- Add x-cloak and custom styles -->
<style>
    [x-cloak] { display: none !important; }
    .frosted-glass {
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        background-color: rgba(255, 255, 255, 0.75);
    }
    .hero-gradient {
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
    }
</style>




<div class="min-h-screen bg-white"
     x-data="{ showDeleteModal: false }">

@if (session('success'))
<div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md">
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg relative"
         style="animation: fadeInOut 5s forwards;">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
        <div class="mt-2">
            <div class="slider" style="width: 100%; height: 2px; background-color: #e2e8f0;">
                <div class="slider-fill" style="width: 100%; height: 100%; background-color: #48bb78; transition: width 5s linear;"></div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(-100%); }
    10% { opacity: 1; transform: translateY(0); }
    90% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-100%); }
}
</style>
@endif
 
     <!-- Hero Section -->
    <div class="relative h-[50vh] bg-black">
        @if($menu->image)
            <img src="{{ asset('storage/' . $menu->image) }}" 
                 alt="{{ $menu->name }}" 
                 class="w-full h-full object-cover opacity-90">
        @else
            <div class="w-full h-full bg-gradient-to-r from-gray-900 to-gray-800"></div>
        @endif
        <div class="absolute inset-0 hero-gradient"></div>

        
        <!-- Floating Header -->
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-4xl mx-auto">
                <a href="{{ route('menus.index') }}" 
                   class="inline-flex items-center text-sm text-black hover:gray-800 mb-4 transition-colors group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                    Menu
                </a>
                <h1 class="text-5xl font-medium text-black tracking-tight">{{ $menu->name }}</h1>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 -mt-8">
        @if (session('error'))
            <div class="mb-6 frosted-glass rounded-2xl p-4 text-red-600 shadow-lg">
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
        <div class="frosted-glass rounded-3xl shadow-xl overflow-hidden">
            <!-- Details Grid -->
            <div class="p-8 space-y-8">
                <!-- Price and Category Row -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-4xl font-medium text-gray-900">
                            €{{ number_format($menu->price, 2) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Prezzo include IVA</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ match($menu->category) {
                                'antipasto' => 'bg-green-100/50 text-green-700',
                                'primo' => 'bg-blue-100/50 text-blue-700',
                                'secondo' => 'bg-red-100/50 text-red-700',
                                'contorno' => 'bg-yellow-100/50 text-yellow-700',
                                'dolce' => 'bg-pink-100/50 text-pink-700',
                                'bevande' => 'bg-purple-100/50 text-purple-700',
                                default => 'bg-gray-100/50 text-gray-700'
                            } }}">
                            {{ ucfirst($menu->category) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $menu->is_available 
                                ? 'bg-green-100/50 text-green-700' 
                                : 'bg-red-100/50 text-red-700' }}">
                            {{ $menu->is_available ? 'Disponibile' : 'Non disponibile' }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-600 leading-relaxed">
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
                                           bg-gray-100/50 text-gray-800">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6 bg-gray-50/80 border-t border-gray-200/50 flex justify-end space-x-4">
                <a href="{{ route('menus.edit', $menu->id) }}" 
                   class="inline-flex items-center px-6 py-3 rounded-full text-sm font-medium 
                          bg-gray-900 text-white hover:bg-gray-800 transition-colors duration-200">
                    Modifica
                </a>
                <button type="button" @click="showDeleteModal = true"
                        class="inline-flex items-center px-6 py-3 rounded-full text-sm font-medium 
                               bg-white text-red-600 border border-red-200 hover:bg-red-50 
                               transition-colors duration-200">
                    Elimina
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-cloak x-show="showDeleteModal" 
         class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity z-50"
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
                     class="relative transform overflow-hidden rounded-2xl bg-white/80 backdrop-blur-xl 
                            px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center 
                                  rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" 
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium text-gray-900">
                                Elimina Piatto
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Sei sicuro di voler eliminare questo piatto? 
                                    Questa azione non può essere annullata.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" 
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex w-full justify-center rounded-full bg-red-600 
                                           px-6 py-3 text-sm font-medium text-white shadow-sm 
                                           hover:bg-red-700 sm:ml-3 sm:w-auto">
                                Elimina
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-full bg-white 
                                       px-6 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 
                                       ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Annulla
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    // Top message auto hide
    document.addEventListener('DOMContentLoaded', function() {
            var sliderFill = document.querySelector('.slider-fill');
            var duration = 5000; // 5 seconds in milliseconds

            setTimeout(function(){
                sliderFill.style.width = '0%';
            }, 0);
        });
</script>
@endsection