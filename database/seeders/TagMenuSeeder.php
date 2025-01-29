<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Tag;
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
        // Get all existing menu and tag IDs
        $menuIds = Menu::pluck('id')->toArray();
        $tagIds = Tag::pluck('id')->toArray();

        $tagMenuPairs = [];
        
        // Create relationships only for existing menus
        foreach ($menuIds as $menuId) {
            // Randomly assign 1-3 tags to each menu
            $numberOfTags = rand(1, 3);
            $selectedTags = array_rand(array_flip($tagIds), min($numberOfTags, count($tagIds)));
            
            if (!is_array($selectedTags)) {
                $selectedTags = [$selectedTags];
            }

            foreach ($selectedTags as $tagId) {
                $tagMenuPairs[] = [
                    'menu_id' => $menuId,
                    'tag_id' => $tagId,
                ];
            }
        }

        DB::table('menu_tag')->insert($tagMenuPairs);
    }
}