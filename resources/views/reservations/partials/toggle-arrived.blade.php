<form method="POST" action="{{ route('reservations.toggleArrived', $reservation) }}" class="flex items-center">
    @csrf
    @method('PATCH')
    <button type="submit" 
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $reservation->arrived ? 'bg-green-500' : 'bg-gray-200' }}"
            role="switch"
            aria-checked="{{ $reservation->arrived ? 'true' : 'false' }}">
        <span class="sr-only">Arrived status</span>
        <span aria-hidden="true" 
              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $reservation->arrived ? 'translate-x-5' : 'translate-x-0' }}">
        </span>
    </button>
</form> 