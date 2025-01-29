<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuApiController extends Controller
{
    private static $categories = [
        'antipasto' => ['emoji' => '🥗', 'name' => 'Antipasto'],
        "pizza" => ['emoji' => '🍕', 'name' => 'Pizza'],
        'primo' => ['emoji' => '🍝', 'name' => 'Primo'],
        'secondo' => ['emoji' => '🥩', 'name' => 'Secondo'],
        'contorno' => ['emoji' => '🥬', 'name' => 'Contorno'],
        'dolce' => ['emoji' => '🍰', 'name' => 'Dolce'],
        'bevande' => ['emoji' => '🥤', 'name' => 'Bevande']
    ];

    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => self::$categories
        ]);
    }

    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('tags')) {
            $tags = explode(',', $request->input('tags'));
            $query->whereHas('tags', function($q) use ($tags) {
                $q->whereIn('name', $tags);
            });
        }

        // Filtra solo gli elementi disponibili
        $query->where('is_available', true);

        // Recupera i menu con i tag e aggiungi l'URL completo dell'immagine e info categoria
        $menus = $query->with('tags')->get()->map(function ($menu) {
            $menu->image_url = $menu->image ? asset('storage/' . $menu->image) : null;
            $menu->category_info = self::$categories[$menu->category] ?? null;
            return $menu;
        });

        return response()->json([
            'success' => true,
            'data' => $menus,
        ]);
    }

    public function show($id)
    {
        $menu = Menu::with('tags')->find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found',
            ], 404);
        }

        $menu->image_url = $menu->image ? asset('storage/' . $menu->image) : null;
        $menu->category_info = self::$categories[$menu->category] ?? null;

        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
    }
}