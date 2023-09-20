<?php

namespace Webkul\Preparation\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Preparation table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Preparation\\Database\\Seeders\\PreparationTableSeeder
 */
class PreparationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('preparations')->delete();

        DB::table('preparation_translations')->delete();

        $now = Carbon::now();

        DB::table('preparations')->insert([
            [
                'id'         => '1',
                'position'   => '1',
                'image'      => NULL,
                'status'     => '1',
                '_lft'       => '1',
                '_rgt'       => '14',
                'parent_id'  => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('preparation_translations')->insert([
            [
                'name'             => 'Root',
                'slug'             => 'root',
                'description'      => 'Root',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'preparation_id'      => '1',
                'locale'           => 'en',
            ],
            [
                'name'             => 'Raíz',
                'slug'             => 'root',
                'description'      => 'Raíz',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'preparation_id'      => '1',
                'locale'           => 'es',
            ],
            [
                'name'             => 'Racine',
                'slug'             => 'root',
                'description'      => 'Racine',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'preparation_id'      => '1',
                'locale'           => 'fr',
            ],
            [
                'name'             => 'Hoofdcategorie',
                'slug'             => 'root',
                'description'      => 'Hoofdcategorie',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'preparation_id'      => '1',
                'locale'           => 'nl',
            ],
            [
                'name'             => 'Kök',
                'slug'             => 'root',
                'description'      => 'Kök',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'preparation_id'      => '1',
                'locale'           => 'tr',
            ]
        ]);
    }
}
