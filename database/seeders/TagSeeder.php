<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Facades\File;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Leggi i dati dei tag dal file JSON
        $tags = file_get_contents(database_path('seeders/data/tags_data.json'));
        $data = json_decode($tags, true);

        // Inserisci i tag nel database
        foreach ($data as $item) {
            Tag::create($item);
        }
    }
}