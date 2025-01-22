@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-6">Menu List</h1>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtro e ricerca -->
    <div class="mb-6 flex justify-between gap-4">
        <!-- Form di filtro -->
        <form action="{{ route('menus.index') }}" method="GET" class="flex gap-4 items-center">
            <!-- Categoria -->
            <select name="category" class="border-gray-300 rounded px-3 py-2">
                <option value="">Seleziona Categoria</option>
                <option value="antipasto" {{ request('category') == 'antipasto' ? 'selected' : '' }}>Antipasto</option>
                <option value="primo" {{ request('category') == 'primo' ? 'selected' : '' }}>Primo</option>
                <option value="secondo" {{ request('category') == 'secondo' ? 'selected' : '' }}>Secondo</option>
                <option value="contorno" {{ request('category') == 'contorno' ? 'selected' : '' }}>Contorno</option>
                <option value="dolce" {{ request('category') == 'dolce' ? 'selected' : '' }}>Dolce</option>
                <option value="bevande" {{ request('category') == 'bevande' ? 'selected' : '' }}>Bevande</option>
            </select>

            <!-- Tag -->
            <select name="tags[]" multiple class="border-gray-300 rounded px-3 py-2">
                <option value="">Seleziona Tag</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, request('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>

            <!-- Ricerca per nome -->
            <input type="text" name="search" placeholder="Cerca per nome..." value="{{ request('search') }}" class="border-gray-300 rounded px-3 py-2">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filtra</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-left">Price</th>
                    <th class="py-3 px-6 text-left">Categoria</th> <!-- Aggiunta colonna categoria -->
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($menus as $menu)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $menu->id }}</td>
                        <td class="py-3 px-6 text-left">{{ $menu->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $menu->description }}</td>
                        <td class="py-3 px-6 text-left">€{{ number_format($menu->price, 2) }}</td>
                        <td class="py-3 px-6 text-left">{{ $menu->category }}</td> <!-- Visualizzazione della categoria -->
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ url('menus/' . $menu->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">View</a>
                                <a href="{{ url('menus/' . $menu->id . '/edit') }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</a>
                                <form action="{{ url('menus/' . $menu->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ url('menus/create') }}" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
            Add New Menu
        </a>
    </div>
</div>
@endsection