@extends('layouts.app')

@section('content')

<style>
    [x-cloak] { display: none !important; }

</style>

<div class="container mx-auto mt-8 mb-[100px]" x-data="{ 
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
                    <div class="slider-fill" style="width: 100%; height: 100%; background-color: #48bb78; animation: slideOut 5s linear forwards;"></div>
                </div>
            </div>
        </div>
    </div>
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
                
                    <div id="tableView" class="overflow-x-auto rounded-xl shadow-lg border border-gray-100">
                <table class="w-full bg-white divide-y divide-gray-200/70">
                    <thead class="bg-gray-50/80 backdrop-blur-sm sticky top-0">
                        <tr class="text-gray-700 text-sm font-semibold">
                            <th class="p-4 w-16"></th>
                            <th class="p-4 text-left min-w-[200px]">Menu Item</th>
                            <th class="p-4 text-left hidden lg:table-cell">Price</th>
                            <th class="p-4 text-left hidden md:table-cell">Tags</th>
                            <th class="p-4 text-left hidden xl:table-cell">Category</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 text-sm">
                        @foreach ($menus as $menu)
                        <tr class="hover:bg-gray-50/50 transition-all duration-200 ease-out {{ $menu->is_available ? '' : 'opacity-70' }}"
                            x-data="{ showActions: false }"
                            @mouseenter="showActions = true"
                            @mouseleave="showActions = false">
                            <td class="p-4">
                                <div class="flex items-center">
                                        @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" loading="lazy" class="h-12 w-12 rounded-lg object-cover shadow-sm border border-gray-200">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 transition-transform duration-300 ease-in-out group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                </div>
                            </td>
                            <td class="p-4 font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    @if (!$menu->is_available)
                                    <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                    @endif
                                    <span class="{{ $menu->is_available ? '' : 'line-through decoration-2' }}">{{ $menu->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-gray-700 hidden lg:table-cell">
                                <span class="font-mono">€{{ number_format($menu->price, 2) }}</span>
                            </td>
                            <td class="p-4 hidden md:table-cell">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($menu->tags as $tag)
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium transition-colors hover:bg-indigo-100">
                                        {{ $tag->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4 hidden xl:table-cell">
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $categoryStyles = [
                                            'antipasto' => 'bg-amber-100 text-amber-900',
                                            'primo' => 'bg-orange-100 text-orange-900',
                                            'secondo' => 'bg-rose-100 text-rose-900',
                                            'contorno' => 'bg-emerald-100 text-emerald-900',
                                            'dolce' => 'bg-pink-100 text-pink-900',
                                            'bevande' => 'bg-sky-100 text-sky-900'
                                        ];
                                    @endphp
                                    @foreach (explode(',', $menu->category) as $category)
                                    @php
                                        $categoryKey = trim(strtolower($category));
                                        $style = $categoryStyles[$categoryKey] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $style }}">
                                        {{ ucfirst($categoryKey) }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-1"
                                    x-show="showActions"
                                    x-transition.opacity>
                                    <a href="{{ url('menus/' . $menu->id) }}" 
                                    class="p-2 text-gray-500 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors"
                                    title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ url('menus/' . $menu->id . '/edit') }}" 
                                    class="p-2 text-gray-500 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition-colors"
                                    title="Edit item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                    <button @click="showDeleteModal = true; currentMenuId = {{ $menu->id }}" 
                                            class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                            title="Delete item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
        <div id="cardView" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($menus as $menu)
                <div class="relative group h-full">
                    <a href="{{ url('menus/' . $menu->id) }}" class="block h-full no-underline">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-lg {{ $menu->is_available ? '' : 'opacity-75' }} h-full flex flex-col">
                            <!-- Image or Fallback -->
                            <div class="w-full h-48 bg-gray-100 rounded-xl overflow-hidden mb-4 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-[1.03]">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 transition-transform duration-300 ease-in-out group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
            
                            <!-- Card Content -->
                            <div class="flex-grow flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-xl text-gray-700 {{ $menu->is_available ? '' : 'line-through' }} transition-colors duration-300 ease-in-out group-hover:text-gray-900">{{ $menu->name }} @if (!$menu->is_available)
                                        <span class="inline-block h-2 w-2 rounded-full bg-red-500 mb-2"></span>
                                    @endif</h3>
                                    <p class="text-lg font-medium text-gray-700 transition-colors duration-300 ease-in-out group-hover:text-gray-900">€{{ number_format($menu->price, 2) }}</p>
                                </div>
                                
                                
    
                                <!-- Categories -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @php
                                        $categories = [
                                            'antipasto' => ['🥗', 'Antipasto', 'bg-yellow-100 text-yellow-800'],
                                            'primo' => ['🍝', 'Primo', 'bg-orange-100 text-orange-800'],
                                            'secondo' => ['🥩', 'Secondo', 'bg-red-100 text-red-800'],
                                            'contorno' => ['🥬', 'Contorno', 'bg-green-100 text-green-800'],
                                            'dolce' => ['🍰', 'Dolce', 'bg-pink-100 text-pink-800'],
                                            'bevande' => ['🥤', 'Bevande', 'bg-blue-100 text-blue-800']
                                        ];
                                    @endphp
                                    @foreach (explode(',', $menu->category) as $category)
                                        @php
                                            $category = trim(strtolower($category));
                                            $categoryInfo = $categories[$category] ?? ['🍽️', ucfirst($category), 'bg-gray-100 text-gray-800'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $categoryInfo[2] }} transition-all duration-300 ease-in-out hover:shadow-md hover:scale-105">
                                            {{ $categoryInfo[0] }} {{ $categoryInfo[1] }}
                                        </span>
                                    @endforeach
                                </div>
    
                                <!-- Tags -->
                                <div class="flex flex-wrap gap-2 mt-auto">
                                    @foreach ($menu->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 transition-all duration-300 ease-in-out hover:bg-gray-200 hover:shadow-md hover:scale-105">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </a>
    
                    <!-- Floating Action Buttons -->
                    <div class="absolute top-2 right-2 flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300 ease-in-out">
                        <a href="{{ url('menus/' . $menu->id . '/edit') }}" class="bg-white text-yellow-600 hover:text-yellow-800 p-2 rounded-full shadow-md transition-all duration-300 ease-in-out hover:scale-110 hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <button type="button" @click.stop="showDeleteModal = true; currentMenuId = {{ $menu->id }}" class="bg-white text-red-600 hover:text-red-800 p-2 rounded-full shadow-md transition-all duration-300 ease-in-out hover:scale-110 hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                        <form :action="'/menus/' + currentMenuId" method="POST" class="inline-block w-full sm:w-auto">
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