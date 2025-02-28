<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-3">
            <h2 class="text-xl font-medium text-gray-800 tracking-tight">
                {{ __('Prenotazioni Tavoli') }}
            </h2>
            <a href="{{ route('reservations.create') }}" 
               class="group bg-gradient-to-b from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-800 text-sm py-2 px-4 rounded-md border border-gray-200 shadow-sm transition-all duration-300 ease-out flex items-center gap-2 hover:scale-[1.02] hover:shadow-md"
               style="text-decoration: none;">
                <span class="h-5 w-5 rounded-full bg-gray-800 group-hover:bg-gray-700 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14m-7-7h14"></path>
                    </svg>
                </span>
                <span>Nuova Prenotazione</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ showDeleteModal: false, currentReservationId: null, reservationName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
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
                            <div class="slider-fill" style="width: 100%; height: 100%; background-color: #48bb78; animation: slideOut 5s linear forwards;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Today's Reservations -->
            @if($reservations->where('date', today())->count() > 0)
                @php
                    $todayReservations = $reservations->where('date', today());
                    $totalGuests = $todayReservations->sum('party_size');
                    $arrivedGuests = $todayReservations->where('arrived', true)->sum('party_size');
                    $pendingGuests = $todayReservations->where('arrived', false)->sum('party_size');
                @endphp
                <div class="bg-white overflow-hidden rounded-2xl shadow-sm">
                    <div class="p-6">
                        <div class="flex flex-col space-y-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Prenotazioni di Oggi</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <span class="inline-block h-3 w-3 rounded-full bg-amber-400 mr-2"></span>
                                        <span class="text-xs text-gray-500">Pranzo</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-block h-3 w-3 rounded-full bg-indigo-400 mr-2"></span>
                                        <span class="text-xs text-gray-500">Cena</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Guests Statistics -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="text-sm font-medium text-gray-500">Ospiti Totali</div>
                                    <div class="mt-2 flex items-baseline">
                                        <span class="text-2xl font-bold text-gray-900">{{ $totalGuests }}</span>
                                        <span class="ml-2 text-sm text-gray-500">persone</span>
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="text-sm font-medium text-green-600">Arrivati</div>
                                    <div class="mt-2 flex items-baseline">
                                        <span class="text-2xl font-bold text-green-700">{{ $arrivedGuests }}</span>
                                        <span class="ml-2 text-sm text-green-600">persone</span>
                                    </div>
                                </div>
                                <div class="bg-amber-50 rounded-xl p-4">
                                    <div class="text-sm font-medium text-amber-600">In Arrivo</div>
                                    <div class="mt-2 flex items-baseline">
                                        <span class="text-2xl font-bold text-amber-700">{{ $pendingGuests }}</span>
                                        <span class="ml-2 text-sm text-amber-600">persone</span>
                                    </div>
                                </div>
                            </div>

                            <!-- In Arrivo Section -->
                            @php
                                $pendingReservations = $reservations->where('date', today())->where('arrived', false);
                            @endphp
                            @if($pendingReservations->count() > 0)
                                <div class="mb-8">
                                    <h4 class="text-sm font-medium text-gray-500 mb-4">In Arrivo ({{ $pendingReservations->count() }})</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($pendingReservations as $reservation)
                                            @include('reservations.partials.reservation-card', [
                                                'reservation' => $reservation,
                                                'isEvening' => $reservation->time->format('H') >= 17,
                                                'partySize' => $reservation->party_size,
                                                'isToday' => true
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Arrivati Section -->
                            @php
                                $arrivedReservations = $reservations->where('date', today())->where('arrived', true);
                            @endphp
                            @if($arrivedReservations->count() > 0)
                                <div class="pt-6 border-t border-gray-100">
                                    <h4 class="text-sm font-medium text-gray-500 mb-4">Arrivati ({{ $arrivedReservations->count() }})</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-75">
                                        @foreach($arrivedReservations as $reservation)
                                            @include('reservations.partials.reservation-card', [
                                                'reservation' => $reservation,
                                                'isEvening' => $reservation->time->format('H') >= 17,
                                                'partySize' => $reservation->party_size,
                                                'isToday' => true
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Empty State for Today -->
                            @if($pendingReservations->count() === 0 && $arrivedReservations->count() === 0)
                                <div class="text-center py-8">
                                    <p class="text-gray-500">Nessuna prenotazione per oggi</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tomorrow's Reservations -->
            @php
                $tomorrowReservations = $reservations->where('date', today()->addDay());
            @endphp
            @if($tomorrowReservations->count() > 0)
                <div class="bg-white overflow-hidden rounded-2xl shadow-sm">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Prenotazioni di Domani</h3>
                            
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($tomorrowReservations as $reservation)
                                @include('reservations.partials.reservation-card', [
                                    'reservation' => $reservation,
                                    'isEvening' => $reservation->time->format('H') >= 17,
                                    'partySize' => $reservation->party_size
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Future Reservations -->
            @php
                $futureReservations = $reservations->where('date', '>', today()->addDay());
            @endphp
            @if($futureReservations->count() > 0)
                <div class="bg-white overflow-hidden rounded-2xl shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Prenotazioni Future</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($futureReservations->sortBy('date') as $reservation)
                                @include('reservations.partials.reservation-card', [
                                    'reservation' => $reservation,
                                    'isEvening' => $reservation->time->format('H') >= 17,
                                    'partySize' => $reservation->party_size
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Past Reservations -->
            @php
                $pastReservations = $reservations->where('date', '<', today());
            @endphp
            @if($pastReservations->count() > 0)
                <div class="bg-white overflow-hidden rounded-2xl shadow-sm opacity-75">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Prenotazioni Passate</h3>
                            <button type="button"
                                    @click="showDeleteModal = true; currentReservationId = 'all'; reservationName = 'tutte le prenotazioni passate'"
                                    class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Elimina Tutte
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pastReservations->sortByDesc('date') as $reservation)
                                @include('reservations.partials.reservation-card', [
                                    'reservation' => $reservation,
                                    'isEvening' => $reservation->time->format('H') >= 17,
                                    'partySize' => $reservation->party_size
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($reservations->count() === 0)
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="text-center">
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Nessuna Prenotazione</h3>
                        <p class="mt-1 text-sm text-gray-500">Il ristorante è in attesa di ospiti</p>
                        <a href="{{ route('reservations.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800">
                            Crea Prima Prenotazione
                        </a>
                    </div>
                </div>
            @endif

            <!-- Modified Delete Confirmation Modal -->
            <div x-cloak
                 x-show="showDeleteModal" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="showDeleteModal"
                             class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                        Cancella Prenotazione
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Sei sicuro di voler cancellare <span x-text="reservationName" class="font-medium"></span>? Questa azione non può essere annullata.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <form x-bind:action="currentReservationId === 'all' ? '{{ route('reservations.destroyPast') }}' : '/reservations/' + currentReservationId" 
                                      method="POST" 
                                      class="inline-block w-full sm:w-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto">
                                        Cancella Prenotazione
                                    </button>
                                </form>
                                <button type="button" @click="showDeleteModal = false"
                                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                    Mantieni Prenotazione
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>