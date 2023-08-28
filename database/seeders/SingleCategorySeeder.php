<?php

namespace Database\Seeders;

use App\Models\SingleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SingleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /////////////////
        SingleCategory::create([
            "title" => "category women clothing",
            "name" => " لباس زنانه",
            "parent_category_id" => 1
        ]);
        SingleCategory::create([
            "title" => "category women shoes",
            "name" => "کفش زنانه",
            "parent_category_id" => 1
        ]);
        SingleCategory::create([
            "title" => "category women accessories",
            "name" => "اکسسوری زنانه",
            "parent_category_id" => 1
        ]);
        SingleCategory::create([
            "title" => "category women sports",
            "name" => " ورزشی زنانه",
            "parent_category_id" => 1
        ]);
        // men
        // /////////////////
        SingleCategory::create([
            "title" => "category men clothing",
            "name" => "لباس مردانه",
            "parent_category_id" => 2
        ]);
        SingleCategory::create([
            "title" => "category men shoes",
            "name" => " کفش مردانه",
            "parent_category_id" => 2
        ]);
        SingleCategory::create([
            "title" => "category men accessories",
            "name" => " اکسسوری مردانه",
            "parent_category_id" => 2
        ]);
        SingleCategory::create([
            "title" => "category men sports",
            "name" => " ورزشی مردانه",
            "parent_category_id" => 2
        ]);
        // kids
        // /////////////////
        SingleCategory::create([
            "title" => "category baby clothing",
            "name" => "نوزاد",
            "parent_category_id" => 3
        ]);
        SingleCategory::create([
            "title" => "category girls shoes",
            "name" => "دخترانه",
            "parent_category_id" => 3
        ]);
        SingleCategory::create([
            "title" => "category boys accessories",
            "name" => "پسرانه",
            "parent_category_id" => 3
        ]);
        // Beauty
        // /////////////////
        SingleCategory::create([
            "title" => "category beauty",
            "name" => " لوازم آرایشی",
            "parent_category_id" => 4
        ]);
        SingleCategory::create([
            "title" => "category personal care",
            "name" => "  لوازم بهداشتی",
            "parent_category_id" => 4
        ]);
        SingleCategory::create([
            "title" => "category electrical personal care",
            "name" => " لوازم شخصی برقی ",
            "parent_category_id" => 4
        ]);
        SingleCategory::create([
            "title" => "category perfume all",
            "name" => " عطر و ادکلن  ",
            "parent_category_id" => 4
        ]);
        SingleCategory::create([
            "title" => "category health care",
            "name" => " ابزار سلامت  ",
            "parent_category_id" => 4
        ]);    
    }
}
