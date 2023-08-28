<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(2)->create();

        $this->call([
            // ParentCategorySeeder::class,
            // CategorySeeder::class,
            // SubCategorySeeder::class,
            // ChildCategorySeeder::class,
            // BrandSeeder::class,
            // SingleCategorySeeder::class,
            // CollectionSeeder::class,
            // SizeSeeder::class,
            ChildCategoryFilterSeeder::class
        ]);
    }
}
