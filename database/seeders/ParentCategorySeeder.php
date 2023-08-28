<?php

namespace Database\Seeders;

use App\Models\ParentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParentCategory::create([
            'title' => 'category-apparel',
            'name' => ' مد و پوشاک ',
            'parent_id' => 0
        ]);
        ParentCategory::create([
            'title' => 'category-uni-clothing',
            'name' => ' مردانه و زنانه ',
            'parent_id' => 1
        ]);
        ParentCategory::create([
            'title' => 'womens-apparel-shop',
            'name' => ' زنانه ',
            'parent_id' => 1
        ]);
        ParentCategory::create([
            'title' => 'mens-apparel-shop',
            'name' => ' مردانه ',
            'parent_id' => 1
        ]);
        ParentCategory::create([
            'title' => 'kids-apparel-shop',
            'name' => ' بچه گانه ',
            'parent_id' => 1
        ]);
        ParentCategory::create([
            'title' => 'personal-appliance-shop',
            'name' => ' زیبایی و سلامت ',
        ]);
    }
}
