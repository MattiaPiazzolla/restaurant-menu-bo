@extends('layouts.app')

@section('content')



<div class=" bg-gradient-to-b from-gray-50 to-white px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-12">
            <a href="{{ route('menus.index') }}" class="inline-flex items-center text-lg text-gray-500 hover:text-gray-900 mb-8 group">
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
                isDragging: false,
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
                        const priceValid = this.price && !isNaN(parseFloat(this.price));
                        return nameValid && descValid && priceValid;
                    }
                    if (this.step === 2) {
                        const categoryValid = document.querySelector('input[name=category]:checked') !== null;
                        return categoryValid;
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
                },
                handleDrop(e) {
                    e.preventDefault();
                    this.isDragging = false;
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        document.getElementById('image').files = e.dataTransfer.files;
                        this.imagePreview = URL.createObjectURL(file);
                    }
                },
                handleDragOver(e) {
                    e.preventDefault();
                    this.isDragging = true;
                },
                handleDragLeave(e) {
                    e.preventDefault();
                    this.isDragging = false;
                }
            }"
            @keydown="handleEnter"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Progress Bar -->
            <div class="mb-10">
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="h-1.5 bg-gray-100 rounded-full">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-400 ease-out"
                            :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                    </div>

                    <!-- Steps -->
                    <div class="absolute -top-2 inset-x-0 flex justify-between">
                        <template x-for="i in 3" :key="i">
                            <button type="button" 
                                @click="step = i"
                                class="flex items-center justify-center group">
                                <!-- Step Marker -->
                                <div class="w-6 h-6 flex items-center justify-center rounded-full transition-all duration-300"
                                    :class="{
                                        'border-2 border-blue-500 bg-white': step === i,
                                        'bg-blue-500': step > i,
                                        'border-2 border-gray-300 bg-white group-hover:border-blue-200': step < i
                                    }">
                                    <!-- Number Indicator -->
                                    <span class="text-sm font-medium leading-none"
                                        :class="{
                                            'text-blue-500': step === i,
                                            'text-white': step > i,
                                            'text-gray-400 group-hover:text-blue-400': step < i
                                        }" x-text="i"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Step 1: Basic Info -->
            <div x-show="step === 1" class="space-y-8 min-h-10">
                <div class="flex gap-2">
                    <div class="group w-1/2">
                        <div class="flex justify-between items-baseline mb-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nome del Piatto <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <input type="text" name="name" id="name" x-ref="nameInput"
                        class="block w-full px-4 py-3 bg-gray-50 ring-1 ring-blue-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all"
                        value="{{ old('name', $menu->name) }}" required>
                    </div>
                    
                    <div class="group w-1/2">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Prezzo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">€</span>
                            <input type="text" name="price" id="price"
                                x-model="price"
                                @input="formatPrice($event)"
                                class="block w-full pl-8 pr-4 py-3 bg-gray-50 border-0 ring-1 ring-blue-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all"
                                placeholder="0.00" required>
                        </div>
                    </div>

                </div>

                <div class="group">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrizione <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" x-ref="descriptionInput"
                        class="block w-full px-4 py-3 bg-gray-50  ring-1 ring-blue-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all resize-none"
                        rows="3" required>{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="group">
                    <label for="is_available" class="text-sm font-medium text-gray-700 mb-2">
                        <p class="mr-2">Disponibile</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @php
                            $categories = [
                                'antipasto' => ['🥗', 'Antipasto'],
                                'pizza' => ['🍕', 'Pizza'],
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
                                <div class="flex flex-col items-center p-4 bg-white ring-1 ring-blue-200 rounded-xl cursor-pointer
                                    peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all">
                                    <span class="text-2xl mb-2">{{ $details[0] }}</span>
                                    <span class="text-sm font-medium">{{ $details[1] }}</span>
                                </div>
                            </label>
                        @endforeach
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
                                <div class="px-4 py-2 ring-1 ring-blue-200 rounded-full bg-gray-50 cursor-pointer select-none
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
                                bg-gray-50 hover:bg-gray-100 transition-colors"
                            @dragover="handleDragOver"
                            @dragleave="handleDragLeave"
                            @drop="handleDrop">
                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                            <div x-show="!imagePreview" 
                                class="absolute inset-0 flex flex-col items-center justify-center"
                                :class="{ 'bg-blue-50': isDragging }">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Trascina un'immagine qui o clicca per selezionarla</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Validation Alert -->
            <div x-show="showValidationError" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="rounded-md border border-red-100 bg-red-50 p-4 shadow-sm shadow-red-100/50 mt-4"
                role="alert">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 leading-relaxed" 
                        x-text="step === 1 ? 'Inserisci nome, descrizione e prezzo del piatto' : step === 2 ? 'Seleziona una categoria' : 'Compila tutti i campi richiesti'">
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