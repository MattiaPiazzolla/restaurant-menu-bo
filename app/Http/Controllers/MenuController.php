<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Mostra tutti i piatti nel menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Menu::query();
    
        // Define categories
        $categories = [
            'antipasto' => ['🥗', 'Antipasto'],
            'primo' => ['🍝', 'Primo'],
            'pizza' => ['🍕', 'Pizza'],
            'secondo' => ['🥩', 'Secondo'],
            'contorno' => ['🥬', 'Contorno'],
            'dolce' => ['🍰', 'Dolce'],
            'bevande' => ['🥤', 'Bevande']
        ];
    
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('tags', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        // Apply category filter
        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where('category', $category); // Exact match with category
        }
    
        // Apply tags filter (if applicable)
        if ($request->filled('tags')) {
            $tags = $request->input('tags');
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('id', $tags);
            });
        }
    
        $menus = $query->with('tags')->get();
    
        return view('menus.index', compact('menus', 'categories'));
    }

    /**
     * Mostra un piatto specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::with('tags')->findOrFail($id);  
        return view('menus.show', compact('menu'));
    }

    public function create()
    {
        $tags = Tag::all();  
        return view('menus.create', compact('tags'));
    }

    /**
     * Crea un nuovo piatto nel menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',  // Verifica che gli ID dei tag esistano
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validazione immagine
        ]);
    
        // Creazione del nuovo piatto
        $menuData = $request->only(['name', 'description', 'price', 'category']);
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $menuData['name'] . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $filename, 'public'); // Salva l'immagine nello storage pubblico
            $menuData['image'] = $filePath; // Salva il percorso nella colonna "image"
        }
    
        $menu = Menu::create($menuData);
    
        // Associare i tag al piatto
        if ($request->has('tags')) {
            $menu->tags()->attach($request->tags);
        }
    
        // Redirect con un messaggio di successo
        return redirect()->route('menus.index')->with('success', 'Piatto creato con successo!');
    }

    public function edit($id)
{
    $menu = Menu::findOrFail($id);
    $tags = Tag::all();
    $categories = [
        'antipasto' => ['🥗', 'Antipasto'],
        'primo' => ['🍝', 'Primo'],
        'secondo' => ['🥩', 'Secondo'],
        'contorno' => ['🥬', 'Contorno'],
        'dolce' => ['🍰', 'Dolce'],
        'bevande' => ['🥤', 'Bevande']
    ];
    return view('menus.edit', compact('menu', 'tags', 'categories'));
}

    /**
     * Aggiorna un piatto esistente nel menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
    
        // Validazione
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'tags' => 'nullable|array',  
            'tags.*' => 'exists:tags,id',  
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        // Aggiorna i dati del piatto
        $menuData = $request->only(['name', 'description', 'price', 'category', 'is_available']);
    
        // Gestisci il caricamento della nuova immagine
        if ($request->hasFile('image')) {
            // Se esiste una vecchia immagine, rimuovila
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);  // Elimina la vecchia immagine
            }
    
            // Carica la nuova immagine
            $file = $request->file('image');
            $filename = $menuData['name'] . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $filename, 'public'); // Salva l'immagine nello storage pubblico
            $menuData['image'] = $filePath; // Aggiorna il percorso dell'immagine
        }
    
        // Aggiorna il piatto con i nuovi dati
        $menu->update($menuData);
    
        // Associare i tag al piatto
        $menu->tags()->sync($request->tags);
    
        // Redirect con un messaggio di successo
        return redirect()->route('menus.show', $menu->id)->with('success', 'Piatto aggiornato con successo!');
    }

    /**
     * Elimina un piatto dal menu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Trova il piatto
        $menu = Menu::find($id);
    
        // Controlla se il piatto esiste
        if (!$menu) {
            return redirect()->route('menus.index')->with('error', 'Piatto non trovato');
        }
    
        // Elimina l'immagine associata se esiste
        if ($menu->image) {
            // Verifica che l'immagine sia presente nel percorso pubblico e la elimina
            Storage::disk('public')->delete($menu->image);
        }
    
        // Elimina il piatto dal database
        $menu->delete();
    
        // Reindirizza alla pagina dell'index con un messaggio di successo
        return redirect()->route('menus.index')->with('success', 'Piatto eliminato con successo');
    }
}