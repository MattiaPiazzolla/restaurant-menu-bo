<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Leggi il file JSON
        $menu = file_get_contents(database_path('seeders/data/menu_data.json'));

        // Decodifica il JSON in array
        $data = json_decode($menu, true);

        // Aggiungi i piatti nel database utilizzando il modello
        foreach ($data as $item) {
            Menu::create($item);
        }
    }
}