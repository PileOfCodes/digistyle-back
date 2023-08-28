<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
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
        SubCategory::create([
            'title' => 'category-women-clothing',
            'name' => 'لباس زنانه',
            'category_id' => 1,
        ]);
        SubCategory::create([
            'title' => 'category-women-bags',
            'name' => 'کیف زنانه',
            'category_id' => 2,
        ]);
        SubCategory::create([
            'title' => 'category-women-shoes',
            'name' => 'کفش زنانه',
            'category_id' => 3,
        ]);
        SubCategory::create([
            'title' => 'category-women-accessories',
            'name' => 'اکسسوری زنانه',
            'category_id' => 4,
        ]);
        SubCategory::create([
            'title' => 'category-women-watches',
            'name' => 'ساعت زنانه',
            'category_id' => 4,
        ]);
        SubCategory::create([
            'title' => 'category-women-jewelry',
            'name' => 'زیورآلات زنانه',
            'category_id' => 4,
        ]);
        SubCategory::create([
            'title' => 'category-women-sports',
            'name' => 'ورزشی زنانه',
            'category_id' => 5,
        ]);
        // men
        /////////////////
        SubCategory::create([
            'title' => 'category-men-clothing',
            'name' => 'لباس مردانه',
            'category_id' => 6,
        ]);
        SubCategory::create([
            'title' => 'category-men-bags',
            'name' => 'کیف مردانه',
            'category_id' => 7,
        ]);
        SubCategory::create([
            'title' => 'category-men-shoes',
            'name' => 'کفش مردانه',
            'category_id' => 8,
        ]);
        SubCategory::create([
            'title' => 'category-men-accessories',
            'name' => 'اکسسوری مردانه',
            'category_id' => 9,
        ]);
        SubCategory::create([
            'title' => 'category-men-watches',
            'name' => 'ساعت مردانه',
            'category_id' => 9,
        ]);
        SubCategory::create([
            'title' => 'category-men-jewelry',
            'name' => 'زیورآلات مردانه',
            'category_id' => 9,
        ]);
        SubCategory::create([
            'title' => 'category-men-sports',
            'name' => 'ورزشی مردانه',
            'category_id' => 10,
        ]);
        // kids
        /////////////////
        SubCategory::create([
            'title' => 'category-kids-clothing',
            'name' => 'لباس بچگانه',
            'category_id' => 11,
        ]);
        SubCategory::create([
            'title' => 'category-kids-shoes',
            'name' => 'کفش بچگانه',
            'category_id' => 12,
        ]);
        SubCategory::create([
            'title' => 'category-kids-accessories',
            'name' => 'اکسسوری بچگانه',
            'category_id' => 13,
        ]);
        // accessories
        /////////////////
        SubCategory::create([
            'title' => 'category-men-perfume',
            'name' => 'عطر مردانه',
            'category_id' => 14,
        ]);
        SubCategory::create([
            'title' => 'category-women-perfume',
            'name' => 'عطر زنانه',
            'category_id' => 14,
        ]);
        SubCategory::create([
            'title' => 'category-face',
            'name' => ' آرایش صورت ',
            'category_id' => 15,
        ]);
        SubCategory::create([
            'title' => 'category-eye-and-eyebrow',
            'name' => ' آرایش چشم و ابرو ',
            'category_id' => 15,
        ]);
        SubCategory::create([
            'title' => 'category-nail-care',
            'name' => ' بهداشت و زیبایی ناخن ',
            'category_id' => 15,
        ]);
        SubCategory::create([
            'title' => 'category-face-and-body-cream',
            'name' => '  مراقیت پوست ',
            'category_id' => 16,
        ]);
        SubCategory::create([
            'title' => 'category-hair-products',
            'name' => '  آرایش مو ',
            'category_id' => 17,
        ]);
        SubCategory::create([
            'title' => 'category-hair-care',
            'name' => ' مراقبت مو ',
            'category_id' => 17,
        ]);
        SubCategory::create([
            'title' => 'category-electrical-personal-care',
            'name' => ' لوازم برقی آرایشی ',
            'category_id' => 17,
        ]);
        SubCategory::create([
            'title' => 'category-dental-hygienist',
            'name' => ' بهداشت دهان ودندان ',
            'category_id' => 18,
        ]);
        SubCategory::create([
            'title' => 'category-body-care',
            'name' => ' بهداشت و مراقبت بدن ',
            'category_id' => 18,
        ]);
    }
}
