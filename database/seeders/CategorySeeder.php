<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // women
        /////////////////
        Category::create([
            "title" => "cloth",
            "name" => "لباس",
            "parent_category_id" => 1
        ]);
        Category::create([
            "title" => "bag",
            "name" => "کیف",
            "parent_category_id" => 1
        ]);
        Category::create([
            "title" => "shoes",
            "name" => "کفش",
            "parent_category_id" => 1
        ]);
        Category::create([
            "title" => "accessories",
            "name" => "اکسسوری",
            "parent_category_id" => 1
        ]);
        Category::create([
            "title" => "sport",
            "name" => "ورزشی",
            "parent_category_id" => 1
        ]);
        // men
        // /////////////////
        Category::create([
            "title" => "cloth",
            "name" => "لباس",
            "parent_category_id" => 2
        ]);
        Category::create([
            "title" => "bag",
            "name" => "کیف",
            "parent_category_id" => 2
        ]);
        Category::create([
            "title" => "shoes",
            "name" => "کفش",
            "parent_category_id" => 2
        ]);
        Category::create([
            "title" => "accessories",
            "name" => "اکسسوری",
            "parent_category_id" => 2
        ]);
        Category::create([
            "title" => "sport",
            "name" => "ورزشی",
            "parent_category_id" => 2
        ]);
        // kids
        // /////////////////
        Category::create([
            "title" => "cloth",
            "name" => "لباس",
            "parent_category_id" => 3
        ]);
        Category::create([
            "title" => "shoes",
            "name" => "کفش",
            "parent_category_id" => 3
        ]);
        Category::create([
            "title" => "accessories",
            "name" => "اکسسوری",
            "parent_category_id" => 3
        ]);
        // Beauty
        // /////////////////
        Category::create([
            "title" => "perfume",
            "name" => "عطر و ادکلن",
            "parent_category_id" => 4
        ]);
        Category::create([
            "title" => "makeup",
            "name" => "آرایش و گریم",
            "parent_category_id" => 4
        ]);
        Category::create([
            "title" => "skin care",
            "name" => "مراقبت پوست",
            "parent_category_id" => 4
        ]);
        Category::create([
            "title" => "hair care",
            "name" => " آرایش و مراقبت مو",
            "parent_category_id" => 4
        ]);
        Category::create([
            "title" => "personal care",
            "name" => " بهداشت و مراقبت شخصی",
            "parent_category_id" => 4
        ]);
    }
}
