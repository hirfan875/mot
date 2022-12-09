<?php


namespace Database\Seeders;


use App\Service\UserService;
use Illuminate\Database\Seeder;

use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [[
            'id' => Tag::TOP_ID,
            'title' => Tag::TOP,
        ], [
            'id' => Tag::TRENDING_ID,
            'title' => Tag::TRENDING,
        ],
            [
                'id' => Tag::FEATURED_ID,
                'title' => Tag::FEATURED,
            ]];

        foreach ($tags as $tag) {
            $tag = new Tag($tag);
            $tag->is_admin = true;
            $tag->save();
        }
    }

}
