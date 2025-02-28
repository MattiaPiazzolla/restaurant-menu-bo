<x-app-layout>
    <x-slot name="header">
        <h2 class="font-medium text-xl text-gray-800 leading-tight">
            {{ __('Stato del Ristorante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg backdrop-blur-sm bg-white/80">
                <div class="p-10">
                    <div class="flex flex-col items-center justify-center space-y-8">
                        <!-- Status Indicator -->
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 rounded-full flex items-center justify-center {{ $status->is_open ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="text-4xl {{ $status->is_open ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $status->is_open ? '●' : '●' }}
                                </span>
                            </div>
                            <span class="mt-4 text-xl font-light text-gray-800">
                                {{ $status->is_open ? 'Aperto' : 'Chiuso' }}
                            </span>
                        </div>
                        
                        <!-- Toggle Button -->
                        <div class="w-full">
                            <form method="POST" action="{{ route('restaurant-status.toggle') }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium text-sm py-3 px-6 rounded-full transition duration-300 ease-in-out">
                                    {{ $status->is_open ? 'Imposta come Chiuso' : 'Imposta come Aperto' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>