<div class="relative bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition-all duration-200 hover:shadow-md">
    <!-- Time indicator -->
    <div class="absolute top-0 right-0 h-full w-1.5 {{ $isEvening ? 'bg-indigo-400' : 'bg-amber-400' }}"></div>
    
    @if($isToday ?? false)
    <div class="absolute bottom-[15%] right-4 flex items-center space-x-2">
        @include('reservations.partials.toggle-arrived', ['reservation' => $reservation])
    </div>
    @endif
    
    <div class="p-5">
        <!-- Header: Date, Time and Actions -->
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center space-x-4">
                <!-- Date and Time -->
                <div class="text-center px-4 py-2 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $reservation->date->locale('it')->isoFormat('ddd, D MMM') }}
                    </p>
                    <p class="text-xl font-bold text-gray-800 mt-1">
                        {{ $reservation->time->format('H:i') }}
                    </p>
                </div>
                <!-- Table Info -->
                <div class="text-center px-4 py-2 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-500">Tavolo</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $reservation->table_number }}</p>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex space-x-2">
                <a href="{{ route('reservations.edit', $reservation) }}" 
                   class="text-gray-400 hover:text-gray-700 transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
                <button type="button" 
                        @click="showDeleteModal = true; currentReservationId = {{ $reservation->id }}; reservationName = '{{ $reservation->name }}'"
                        class="text-gray-400 hover:text-red-600 transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Guest Info -->
        <div class="flex items-center justify-between border-t border-b border-gray-100 py-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $reservation->name }}</h3>
                <div class="flex items-center mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-600">{{ $partySize }} {{ $partySize == 1 ? 'ospite' : 'ospiti' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes if exists -->
        @if($reservation->note)
            <div class="mt-4">
                <div class="flex items-start bg-gray-50 rounded-lg p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-gray-600">{{ $reservation->note }}</p>
                </div>
            </div>
        @endif
    </div>
</div>