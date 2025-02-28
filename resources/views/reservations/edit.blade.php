<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
            {{ __('Modifica Prenotazione') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6">Modifica Dettagli Prenotazione</h3>
                    
                    <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="date" class="block text-gray-700 font-medium">Data</label>
                                <input type="date" name="date" id="date" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                    required value="{{ old('date', $reservation->date->format('Y-m-d')) }}">
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="time" class="block text-gray-700 font-medium">Ora</label>
                                <input type="time" name="time" id="time" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                    required value="{{ old('time', $reservation->time->format('H:i')) }}">
                                @error('time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <label for="name" class="block text-gray-700 font-medium">Nome Cliente</label>
                            <input type="text" name="name" id="name" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                required value="{{ old('name', $reservation->name) }}" placeholder="Inserisci il nome del cliente">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-2">
                                <label for="party_size" class="block text-gray-700 font-medium">Numero di Ospiti</label>
                                <input type="number" name="party_size" id="party_size" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                    required value="{{ old('party_size', $reservation->party_size) }}" min="1" placeholder="Numero di persone">
                                @error('party_size')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="table_number" class="block text-gray-700 font-medium">Numero Tavolo</label>
                                <input type="number" name="table_number" id="table_number" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                    required value="{{ old('table_number', $reservation->table_number) }}" min="0" placeholder="Assegna numero tavolo">
                                @error('table_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <label for="note" class="block text-gray-700 font-medium">Note Speciali</label>
                            <textarea name="note" id="note" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" 
                                rows="4" placeholder="Richieste speciali o esigenze alimentari">{{ old('note', $reservation->note) }}</textarea>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="submit" 
                                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Aggiorna Prenotazione
                            </button>
                            <a href="{{ route('reservations.index') }}" 
                                class="w-full sm:w-auto text-center py-3 px-6 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-150 ease-in-out">
                                Annulla
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>