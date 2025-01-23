@extends('layouts.app')

@section('content')

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

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-12">
            <a href="{{ route('menus.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 mb-8 group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Torna al menu
            </a>
            <h1 class="text-3xl font-light text-gray-900">Modifica Piatto</h1>
        </div>

        <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
            x-data="{ 
                step: 1,
                price: '{{ old('price', $menu->price) }}',
                imagePreview: '{{ asset('storage/' . $menu->image) }}',
                isAvailable: {{ old('is_available', $menu->is_available) ? 'true' : 'false' }},
                showValidationError: false,
                formatPrice(e) {
                    let value = e.target.value.replace(/[^\d.]/g, '');
                    let parts = value.split('.');
                    if (parts[1] && parts[1].length > 2) {
                        parts[1] = parts[1].substring(0, 2);
                        value = parts.join('.');
                    }
                    this.price = value;
                },
                validateStep() {
                    if (this.step === 1) {
                        const nameValid = this.$refs.nameInput.value.trim() !== '';
                        const descValid = this.$refs.descriptionInput.value.trim() !== '';
                        const categoryValid = document.querySelector('input[name=category]:checked') !== null;
                        return nameValid && descValid && categoryValid;
                    }
                    if (this.step === 2) {
                        return this.price && !isNaN(parseFloat(this.price));
                    }
                    return true;
                },
                handleEnter(e) {
                    if (e.key === 'Enter' && e.target.tagName.toLowerCase() !== 'textarea') {
                        e.preventDefault();
                        if (!this.validateStep()) {
                            this.showValidationError = true;
                            return;
                        }
                        if (this.step < 3) {
                            this.step++;
                            this.showValidationError = false;
                        }
                    }
                }
            }"
            @keydown="handleEnter"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Progress Bar -->
            <div class="relative mb-12">
                <div class="h-1 bg-gray-100 rounded-full">
                    <div class="h-1 bg-blue-500 rounded-full transition-all duration-500"
                        :style="'width: ' + (step * 33.33) + '%'"></div>
                </div>
                <div class="absolute -top-2 left-0 right-0 flex justify-between">
                    <button type="button" @click="step = 1"
                        :class="{'bg-blue-500 border-transparent': step >= 1, 'bg-white border-gray-300': step < 1}"
                        class="w-5 h-5 rounded-full border-2 transition-colors duration-200"></button>
                    <button type="button" @click="step = 2"
                        :class="{'bg-blue-500 border-transparent': step >= 2, 'bg-white border-gray-300': step < 2}"
                        class="w-5 h-5 rounded-full border-2 transition-colors duration-200"></button>
                    <button type="button" @click="step = 3"
                        :class="{'bg-blue-500 border-transparent': step >= 3, 'bg-white border-gray-300': step < 3}"
                        class="w-5 h-5 rounded-full border-2 transition-colors duration-200"></button>
                </div>
            </div>

            <!-- Step 1: Basic Info -->
            <div x-show="step === 1" class="space-y-8">
                <div class="group">
                    <div class="flex justify-between items-baseline mb-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nome del Piatto <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-gray-400" x-text="$refs.nameInput?.value.length || 0"></span>
                    </div>
                    <input type="text" name="name" id="name" x-ref="nameInput"
                        class="block w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all"
                        value="{{ old('name', $menu->name) }}" required>
                </div>

                <div class="group">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrizione <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" x-ref="descriptionInput"
                        class="block w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all resize-none"
                        rows="3" required>{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @php
                            $categories = [
                                'antipasto' => ['🥗', 'Antipasto'],
                                'primo' => ['🍝', 'Primo'],
                                'secondo' => ['🥩', 'Secondo'],
                                'contorno' => ['🥬', 'Contorno'],
                                'dolce' => ['🍰', 'Dolce'],
                                'bevande' => ['🥤', 'Bevande']
                            ];
                        @endphp

                        @foreach($categories as $value => $details)
                            <label class="relative">
                                <input type="radio" name="category" value="{{ $value }}"
                                    class="peer sr-only" {{ old('category', $menu->category) == $value ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-4 bg-gray-50 rounded-xl cursor-pointer
                                    peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all">
                                    <span class="text-2xl mb-2">{{ $details[0] }}</span>
                                    <span class="text-sm font-medium">{{ $details[1] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="group">
                    <label for="is_available" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                        <span>Disponibile</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_available" value="0">
                            <input type="checkbox" name="is_available" id="is_available"
                                value="1"
                                x-model="isAvailable"
                                class="sr-only peer"
                                {{ $menu->is_available ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 
                                       peer-focus:ring-blue-300 rounded-full peer 
                                       peer-checked:after:translate-x-full peer-checked:after:border-white 
                                       after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                       after:bg-white after:border-gray-300 after:border after:rounded-full 
                                       after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                <span x-text="isAvailable ? 'Sì' : 'No'"></span>
                            </span>
                        </label>
                    </label>
                </div>
            </div>

            <!-- Step 2: Price and Tags -->
            <div x-show="step === 2" class="space-y-8">
                <div class="group">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Prezzo <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">€</span>
                        <input type="text" name="price" id="price"
                            x-model="price"
                            @input="formatPrice($event)"
                            class="block w-full pl-8 pr-4 py-3 bg-gray-50 border-0 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="0.00" required>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Tag</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="relative">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    class="peer sr-only" 
                                    {{ in_array($tag->id, old('tags', $menu->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-full bg-gray-50 cursor-pointer select-none
                                    peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                    {{ $tag->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Step 3: Image Upload -->
            <div x-show="step === 3" class="space-y-8">
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Immagine del Piatto</label>
                    <div class="relative">
                        <input type="file" name="image" id="image" accept="image/*"
                            class="sr-only" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        <label for="image" 
                            class="relative block w-full aspect-video rounded-xl overflow-hidden cursor-pointer
                                bg-gray-50 hover:bg-gray-100 transition-colors">
                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                            <div x-show="!imagePreview" class="absolute inset-0 flex flex-col items-center justify-center">
                            </div>
                        </label>
                        <p class="mt-2 text-sm text-gray-500">Clicca o tocca sopra per caricare una nuova immagine <i class="fas fa-arrow-up"></i> </p>
                    </div>
                </div>
            </div>

            <!-- Validation Alert -->
            <div x-show="showValidationError" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="rounded-lg bg-red-50 p-4 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Per favore compila tutti i campi obbligatori
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-8">
                <button type="button" 
                    x-show="step > 1"
                    @click="step--; showValidationError = false"
                    class="px-6 py-3 text-gray-600 hover:text-gray-900 transition-colors">
                    Indietro
                </button>
                <button type="button" 
                    x-show="step < 3"
                    @click="if (validateStep()) { step++; showValidationError = false } else { showValidationError = true }"
                    class="ml-auto px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors">
                    Continua
                </button>
                <button type="submit"
                    x-show="step === 3"
                    class="ml-auto px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Aggiorna Piatto
                </button>
            </div>
        </form>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection