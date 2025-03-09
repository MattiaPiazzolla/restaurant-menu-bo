<x-app-layout>
    <div class="min-h-screen bg-gray-50/50">
     <!-- Restaurant Status Section -->
    <div class="max-w-3xl mx-auto px-4 pt-8 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg backdrop-blur-sm bg-white/80 mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $status->is_open ? 'bg-green-50' : 'bg-red-50' }}">
                            <span class="text-2xl {{ $status->is_open ? 'text-green-500' : 'text-red-500' }}">
                                {{ $status->is_open ? '●' : '●' }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Stato del Ristorante</h2>
                            <p class="text-sm text-gray-500">{{ $status->is_open ? 'Aperto' : 'Chiuso' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('restaurant-status.toggle') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            title="Cambia temporaneamente lo stato del ristorante per occasioni speciali"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium text-sm py-2 px-4 rounded-full transition duration-300 ease-in-out">
                            {{ $status->is_open ? 'Imposta come Chiuso' : 'Imposta come Aperto' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Success Message -->
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
        
        <!-- Main Content -->
        <div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <header class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-900">Orari di Apertura</h1>
                <p class="mt-2 text-sm text-gray-600">Gestisci gli orari di apertura settimanali del ristorante</p>
            </header>
            
            <form action="{{ route('schedules.update') }}" method="POST">
                @csrf
                @php
                    $italianDays = [
                        'Monday' => 'Lunedì',
                        'Tuesday' => 'Martedì',
                        'Wednesday' => 'Mercoledì',
                        'Thursday' => 'Giovedì',
                        'Friday' => 'Venerdì',
                        'Saturday' => 'Sabato',
                        'Sunday' => 'Domenica'
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach($schedules as $schedule)
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200" 
                             x-data="{ 
                                isOpen: {{ $schedule->is_open ? 'true' : 'false' }},
                                toggleOpen() {
                                    this.isOpen = !this.isOpen;
                                    this.$refs.checkbox.checked = this.isOpen;
                                }
                             }">
                            <input type="hidden" name="schedules[{{ $schedule->id }}][id]" value="{{ $schedule->id }}">
                            
                            <!-- Day Header -->
                            <div class="flex items-center justify-between p-4 sm:px-6" data-schedule-id="{{ $schedule->id }}">
                                <h3 class="text-base font-medium text-gray-900">
                                    {{ $italianDays[$schedule->day] ?? $schedule->day }}
                                </h3>
                                <div class="flex items-center">
                                    <button type="button" 
                                            @click="toggleOpen"
                                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                            :class="{ 'bg-gray-900': isOpen, 'bg-gray-200': !isOpen }">
                                        <span class="sr-only">Toggle opening status</span>
                                        <span class="translate-x-0 pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                              :class="{ 'translate-x-5': isOpen, 'translate-x-0': !isOpen }">
                                        </span>
                                    </button>
                                    <input type="checkbox" 
                                           x-ref="checkbox"
                                           name="schedules[{{ $schedule->id }}][is_open]" 
                                           value="1" 
                                           class="hidden"
                                           :checked="isOpen">
                                    <span class="ml-3 text-sm text-gray-500" x-text="isOpen ? 'Aperto' : 'Chiuso'"></span>
                                </div>
                            </div>
                            
                            <!-- Time Inputs -->
                            <div class="border-t border-gray-100" x-show="isOpen" x-transition>
                                <div class="p-4 sm:p-6 space-y-4">
                                    <!-- Lunch -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Pranzo</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="relative flex-1">
                                                <input type="time" 
                                                       name="schedules[{{ $schedule->id }}][lunch_opening]" 
                                                       value="{{ $schedule->lunch_opening }}"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:text-sm sm:leading-6"
                                                       onchange="roundTo15(this)"
                                                       onblur="roundTo15(this)">
                                            </div>
                                            <span class="text-gray-500">—</span>
                                            <div class="relative flex-1">
                                                <input type="time" 
                                                       name="schedules[{{ $schedule->id }}][lunch_closing]" 
                                                       value="{{ $schedule->lunch_closing }}"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:text-sm sm:leading-6"
                                                       onchange="roundTo15(this)"
                                                       onblur="roundTo15(this)">
                                            </div>
                                            <button type="button" 
                                                    onclick="resetTimes(this)"
                                                    class="shrink-0 p-1.5 text-gray-400 hover:text-gray-500 rounded-md hover:bg-gray-50">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Dinner -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Cena</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="relative flex-1">
                                                <input type="time" 
                                                       name="schedules[{{ $schedule->id }}][dinner_opening]" 
                                                       value="{{ $schedule->dinner_opening }}"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:text-sm sm:leading-6"
                                                       onchange="roundTo15(this)"
                                                       onblur="roundTo15(this)">
                                            </div>
                                            <span class="text-gray-500">—</span>
                                            <div class="relative flex-1">
                                                <input type="time" 
                                                       name="schedules[{{ $schedule->id }}][dinner_closing]" 
                                                       value="{{ $schedule->dinner_closing }}"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:text-sm sm:leading-6"
                                                       onchange="roundTo15(this)"
                                                       onblur="roundTo15(this)">
                                            </div>
                                            <button type="button" 
                                                    onclick="resetTimes(this)"
                                                    class="shrink-0 p-1.5 text-gray-400 hover:text-gray-500 rounded-md hover:bg-gray-50">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" 
                            class="w-full sm:w-auto flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                        Salva Orari
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript -->
    @push('scripts')
    <script>
        function roundTo15(input) {
            let value = input.value;
            if (value) {
                let [hours, minutes] = value.split(':').map(Number);
                let remainder = minutes % 15;
                minutes = remainder >= 8 ? minutes + (15 - remainder) : minutes - remainder;
                
                if (minutes === 60) {
                    minutes = 0;
                    hours = (hours + 1) % 24;
                }
                
                input.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            }
        }

        function resetTimes(button) {
            const container = button.closest('.space-y-2');
            const inputs = container.querySelectorAll('input[type="time"]');
            inputs.forEach(input => {
                input.value = '';
                input.classList.add('ring-2', 'ring-gray-200');
                setTimeout(() => {
                    input.classList.remove('ring-2', 'ring-gray-200');
                }, 200);
            });
        }

        async function updateSchedule(scheduleId, data) {
            try {
                const response = await fetch(`/api/schedules/${scheduleId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.innerHTML = `
                    <div class="fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        Orario aggiornato con successo
                    </div>
                `;
                document.body.appendChild(successMessage);
                setTimeout(() => successMessage.remove(), 3000);

                return result;
            } catch (error) {
                console.error('Error:', error);
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.innerHTML = `
                    <div class="fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        Errore durante l'aggiornamento
                    </div>
                `;
                document.body.appendChild(errorMessage);
                setTimeout(() => errorMessage.remove(), 3000);
            }
        }

        // Add event listeners to form inputs for automatic updates
        document.querySelectorAll('input[type="time"], input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', async (e) => {
                const scheduleId = e.target.closest('[data-schedule-id]').dataset.scheduleId;
                const container = e.target.closest('.bg-white');
                const data = {
                    is_open: container.querySelector('input[type="checkbox"]').checked,
                    lunch_opening: container.querySelector('input[name$="[lunch_opening]"]').value,
                    lunch_closing: container.querySelector('input[name$="[lunch_closing]"]').value,
                    dinner_opening: container.querySelector('input[name$="[dinner_opening]"]').value,
                    dinner_closing: container.querySelector('input[name$="[dinner_closing]"]').value,
                };
                await updateSchedule(scheduleId, data);
            });
        });
    </script>
    @endpush
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-app-layout>

