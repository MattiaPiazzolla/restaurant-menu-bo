<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Leggi il contenuto del file JSON
        $tagMenuData = file_get_contents(database_path('seeders/data/tag_menu_data.json'));
        $data = json_decode($tagMenuData, true);

        // Inserisci le associazioni nella tabella ponte
        foreach ($data as $item) {
            $menuId = $item['menu_id'];
            foreach ($item['tag_ids'] as $tagId) {
                DB::table('menu_tag')->insert([
                    'menu_id' => $menuId,
                    'tag_id' => $tagId
                ]);
            }
        }
    }
}