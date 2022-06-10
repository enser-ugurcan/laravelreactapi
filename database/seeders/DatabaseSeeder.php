<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'email' => 'admin@admin.com',
            'name' => 'Admin',
            'password' => '$2y$10$5ZzibMZF93J6U1pyldSEZumbi1aBX1OPhoKj2kalhg8U0BsrpuEly',
            'role_as' =>  1
        ]);

        \App\Models\Language::create([
            'name' => 'tr',
            'image' => '',
        ]);

        \App\Models\Language::create([
            'name' => 'eng',
            'image' => '',
        ]);

        \App\Models\Language::create([
            'name' => 'esp',
            'image' => '',
        ]);

        \App\Models\Category::create([]);

        \App\Models\CategoryDescription::create([
            'title' => 'Elektronik',
            'description' => 'Elektronik',
            'category_id' => 1,
            'language_id' => 1
        ]);

        \App\Models\CategoryDescription::create([
            'title' => 'Electronic',
            'description' => 'Electronic',
            'category_id' => 1,
            'language_id' => 2
        ]);

        \App\Models\CategoryDescription::create([
            'title' => 'Electrónico',
            'description' => 'Electrónico',
            'category_id' => 1,
            'language_id' => 3
        ]);

        \App\Models\Category::create(['parent_id' => 1]);

        \App\Models\CategoryDescription::create([
            'title' => 'Televizyon',
            'description' => 'Televizyon',
            'category_id' => 2,
            'language_id' => 1
        ]);

        \App\Models\CategoryDescription::create([
            'title' => 'Television',
            'description' => 'Television',
            'category_id' => 2,
            'language_id' => 2
        ]);

        \App\Models\CategoryDescription::create([
            'title' => 'Televisión',
            'description' => 'Televisión',
            'category_id' => 2,
            'language_id' => 3
        ]);

        \App\Models\Product::create([
            'category_id' => 2,
            'slug' => 'SAMSUNG UE 55AU7000 55inc 138 cm 4K UHD',
            'size' => '55',
            'color' => 'Siyah',
            'name' => 'SAMSUNG UE 55AU7000 55inc 138 cm 4K UHD',
            'brand' => 'SAMSUNG',
            'selling_price' => '1000',
            'original_price' => '1000',
            'qty' => '10',
        ]);
    }
}
