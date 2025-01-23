@extends('layouts.app')

@section('content')

<style>
    [x-cloak] { display: none !important; }
</style>

<div class="container mx-auto mt-8 mb-10" x-data="{ 
    showDeleteModal: false, 
    currentMenuId: null,
    currentSearch: '{{ request('search') }}',
    currentCategory: '{{ request('category') }}',
    currentTags: {{ json_encode(request('tags', [])) }},
    
    removeFilter(type, value = null) {
        if (type === 'search') this.currentSearch = '';
        if (type === 'category') this.currentCategory = '';
        if (type === 'tag') this.currentTags = this.currentTags.filter(id => id !== value);
        this.$refs.filterForm.submit();
    },
    
    toggleTag(tagId) {
        const index = this.currentTags.indexOf(tagId);
        if (index === -1) {
            this.currentTags.push(tagId);
        } else {
            this.currentTags.splice(index, 1);
        }
        this.$refs.filterForm.submit();
    },

    resetAllFilters() {
        this.currentSearch = '';
        this.currentCategory = '';
        this.currentTags = [];
        this.$refs.filterForm.submit();
    }
}">
    <h1 class="text-2xl font-bold mb-6">Menu</h1>

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


    <div x-data="{ isSearchOpen: false }" class="w-full mb-6 relative">
        <!-- Mobile Search Toggle -->
        <button 
            @click="isSearchOpen = !isSearchOpen" 
            class="md:hidden fixed bottom-4 right-4 z-50 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition duration-300"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        <!-- Back to Top Button -->
        <button id="back-to-top" class="fixed bottom-4 left-4 z-50 bg-indigo-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-indigo-700 transition duration-300" title="Back to Top"><i class="fas fa-arrow-up"></i></button>
    
        <!-- Search Form -->
        <div 
        x-show="isSearchOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 z-40 px-1 py-4 md:static md:block bg-white md:bg-transparent overflow-y-auto"
        :class="{'hidden': !isSearchOpen}"
        >
            <form 
                method="GET" 
                action="{{ route('menus.index') }}" 
                class="flex flex-col md:grid md:grid-cols-[1fr_auto_auto_auto] gap-4 items-end p-4 md:p-0 h-full md:h-auto space-y-4 md:space-y-0"
            >
                <!-- Close Button for Mobile -->
                <button 
                    type="button" 
                    @click="isSearchOpen = false" 
                    class="md:hidden self-end text-gray-600 hover:text-gray-900 mb-4"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Search Input -->
                <div class="w-full">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cerca</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Cerca per nome o tag..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300 shadow-lg"
                    >
                </div>

                <!-- Category Filter -->
                <div class="w-full md:w-auto">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                    <select
                        id="category"
                        name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300 shadow-lg"
                    >
                        <option value="">Tutte le categorie</option>
                        @foreach ($categories as $key => $value)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                            {{ $value[1] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="flex w-full md:w-auto space-x-4 md:space-x-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out shadow-lg"
                    >
                        Cerca
                    </button>

                    <button 
                        type="button"
                        onclick="window.location.href='{{ route('menus.index') }}'"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out shadow-lg"
                    >
                        Reset
                    </button>
                </div>
            </form>
        </div>

  <div class="py-4 px-2 flex items-center space-x-4">
            <!-- Toggle View Button with Icon -->
            <button id="toggleView" class="bg-indigo-600 text-white w-10 h-10 rounded-full shadow-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-th-large" title="Toggle View"></i>
            </button>
        
            <!-- Add New Dish Button with Icon -->
            <a href="{{ url('menus/create') }}" class="no-underline bg-green-600 text-white w-10 h-10 rounded-full shadow-lg hover:bg-green-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-plus" title="Add New Dish"></i>
            </a>
        </div>
    
    <div id="tableView" class="overflow-x-auto block">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
            <thead class="bg-gray-50 text-gray-800 uppercase text-xs font-medium tracking-wider">
                <tr>
                    <th class="py-3 px-6 text-left">Nome</th>
                    <th class="py-3 px-6 text-left">Prezzo</th>
                    <th class="py-3 px-6 text-left hidden md:table-cell">Tag</th>
                    <th class="py-3 px-6 text-left hidden md:table-cell">Pasto</th>
                    <th class="py-3 px-6 text-center">Comandi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach ($menus as $menu)
                    <tr class="border-b border-gray-100 hover:bg-indigo-50 transition-colors ease-in-out duration-300">
                        <td class="py-3 px-6 text-left font-medium text-gray-800">{{ $menu->name }}</td>
                        <td class="py-3 px-6 text-left text-gray-600">€{{ number_format($menu->price, 2) }}</td>
                        <td class="py-3 px-6 text-left hidden md:table-cell">
                            <div class="flex flex-wrap space-x-2">
                                @foreach ($menu->tags as $tag)
                                    <span class="inline-block bg-gray-200 text-gray-700 text-xs font-medium py-1 px-3 rounded-full hover:bg-indigo-200 hover:text-indigo-700 transition-all duration-200 ease-in-out">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left hidden md:table-cell">
                            <div class="flex flex-wrap space-x-2">
                                @foreach (explode(',', $menu->category) as $category)
                                    <span class="inline-block bg-gray-200 text-gray-700 text-xs font-medium py-1 px-3 rounded-full hover:bg-blue-200 hover:text-blue-700 transition-all duration-200 ease-in-out">{{ trim($category) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center space-x-4">
                                <a href="{{ url('menus/' . $menu->id) }}" class="text-gray-600 p-3 rounded-full hover:bg-indigo-100 hover:text-indigo-600 transition duration-200 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.5 4.5L15 19m4.5-4.5H3" />
                                    </svg>
                                </a>
                                <a href="{{ url('menus/' . $menu->id . '/edit') }}" class="text-gray-600 p-3 rounded-full hover:bg-yellow-100 hover:text-yellow-600 transition duration-200 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-2 2h8l2-2v-8l-2-2h-8l-2 2v8z" />
                                    </svg>
                                </a>
                                <button type="button" @click="showDeleteModal = true; currentMenuId = {{ $menu->id }}" class="text-gray-600 p-3 rounded-full hover:bg-red-100 hover:text-red-600 transition duration-200 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
        @foreach ($menus as $menu)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-lg transition-all duration-300 ease-in-out">
                <!-- Image or Fallback -->
                <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
    
                <!-- Card Content -->
                <h3 class="font-medium text-lg text-gray-800 mt-4">{{ $menu->name }}</h3>
                <p class="text-gray-600">€{{ number_format($menu->price, 2) }}</p>
                
                <!-- Tags -->
                <div class="flex flex-wrap space-x-2 my-2">
                    @foreach ($menu->tags as $tag)
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs font-medium py-1 px-3 rounded-full">{{ $tag->name }}</span>
                    @endforeach
                </div>
    
                <!-- Categories -->
                <div class="flex flex-wrap space-x-2 my-2">
                    @foreach (explode(',', $menu->category) as $category)
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs font-medium py-1 px-3 rounded-full">{{ trim($category) }}</span>
                    @endforeach
                </div>
    
                <!-- Actions -->
                <div class="flex items-center justify-around mt-4">
                    <a href="{{ url('menus/' . $menu->id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-200 ease-in-out flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.5 4.5L15 19m4.5-4.5H3" />
                        </svg>
                    </a>
                    <a href="{{ url('menus/' . $menu->id . '/edit') }}" class="text-yellow-600 hover:text-yellow-800 transition duration-200 ease-in-out flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-2 2h8l2-2v-8l-2-2h-8l-2 2v8z" />
                        </svg>
                    </a>
                    <button type="button" @click="showDeleteModal = true; currentMenuId = {{ $menu->id }}" class="text-red-600 hover:text-red-800 transition duration-200 ease-in-out flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
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
                        <form :action="'/menus/' + currentMenuId" method="POST" class="inline-block">
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
<script>
    document.getElementById('toggleView').addEventListener('click', function() {
        const tableView = document.getElementById('tableView');
        const cardView = document.getElementById('cardView');
        if (tableView.classList.contains('hidden')) {
            tableView.classList.remove('hidden');
            cardView.classList.add('hidden');
            this.innerHTML = '<i class="fas fa-th-large" title="Switch to Card View"></i>';
        } else {
            tableView.classList.add('hidden');
            cardView.classList.remove('hidden');
            this.innerHTML = '<i class="fas fa-list" title="Switch to Table View"></i>';
        }
    });

    // Show or hide the button based on scroll position
    window.addEventListener('scroll', function () {
        const button = document.getElementById('back-to-top');
        if (window.scrollY > 300) {
            button.style.display = 'block';
        } else {
            button.style.display = 'none';
        }
    });

    // Scroll to the top of the page when the button is clicked
    document.getElementById('back-to-top').addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Smooth scroll effect
        });
    });

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