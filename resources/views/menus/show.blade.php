@extends('layouts.app')

@section('content')
<!-- Add x-cloak styles in the head -->
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white px-4 py-12"
     x-data="{ showDeleteModal: false }">
    <div class="max-w-3xl mx-auto">
        <div class="mb-12">
            <a href="{{ route('menus.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 mb-8 group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Torna alla lista
            </a>
            <h1 class="text-3xl font-light text-gray-900">{{ $menu->name }}</h1>
        </div>

        @if (session('error'))
            <div class="rounded-lg bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Image Section -->
            <div class="aspect-video w-full bg-gray-100">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" 
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="p-8 space-y-6">
                <!-- Description -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Descrizione</h3>
                    <p class="text-gray-900">{{ $menu->description ?? 'Nessuna descrizione disponibile' }}</p>
                </div>

                <!-- Price and Category -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Prezzo</h3>
                        <p class="text-2xl font-medium text-gray-900">€{{ number_format($menu->price, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Categoria</h3>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ match($menu->category) {
                                'antipasto' => 'bg-green-100 text-green-800',
                                'primo' => 'bg-blue-100 text-blue-800',
                                'secondo' => 'bg-red-100 text-red-800',
                                'contorno' => 'bg-yellow-100 text-yellow-800',
                                'dolce' => 'bg-pink-100 text-pink-800',
                                'bevande' => 'bg-purple-100 text-purple-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                            {{ ucfirst($menu->category) }}
                        </div>
                    </div>
                </div>

                <!-- Availability Status -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Stato</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $menu->is_available ? 'Disponibile' : 'Non disponibile' }}
                    </span>
                </div>

                <!-- Tags -->
                @if($menu->tags->isNotEmpty())
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Tag</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($menu->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end space-x-4">
                <a href="{{ route('menus.edit', $menu->id) }}" 
                    class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Modifica
                </a>
                <button type="button" @click="showDeleteModal = true"
                    class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
                    Elimina
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
                        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="inline-block">
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
</div>

<script defer src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection