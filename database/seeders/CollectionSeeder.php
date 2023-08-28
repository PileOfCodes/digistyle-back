<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::create([
            "name" => " مجموعه جدید بهاری",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => null
        ]);

        Collection::create([
            "name" => "  فروش پوشاک بچگانه ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => null
        ]);

        Collection::create([
            "name" => "  لباس و کفش ورزشی   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 1
        ]);

        Collection::create([
            "name" => " سویشرت و هودی   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 1
        ]);

        Collection::create([
            "name" => "  لباس و کفش ورزشی  ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 2
        ]);

        Collection::create([
            "name" => "  هودی ، ژاکت و کاپشن   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 2
        ]);

        Collection::create([
            "name" => "  کفش و نیم بوت بچگانه   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 3
        ]);
        
        Collection::create([
            "name" => "   پوشاک نوزادی   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 3
        ]);

        Collection::create([
            "name" => "   محصولات پاک کننده آرایش صورت   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 4
        ]);

        Collection::create([
            "name" => "   اصلاح مو سر و صورت   ",
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => 4
        ]);
    }
}
