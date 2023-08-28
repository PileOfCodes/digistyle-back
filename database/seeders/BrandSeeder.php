<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'title' => 'gray brand',
            'name' => 'گری',
        ]);
        Brand::create([
            'title' => 'dalavin brand',
            'name' => 'دالاوین',
        ]);
        Brand::create([
            'title' => 'encino brand',
            'name' => 'ان سی نو',
        ]);
        Brand::create([
            'title' => 'degerman brand',
            'name' => 'دگرمان',
        ]);
        Brand::create([
            'title' => 'kikiraiki brand',
            'name' => 'کیکی رایکی',
        ]);
        Brand::create([
            'title' => 'nizel brand',
            'name' => 'نیزل ',
        ]);
        Brand::create([
            'title' => 'bornos b brand',
            'name' => ' ّبرنس',
        ]);
        Brand::create([
            'title' => 'ziboo brand',
            'name' => ' زیبو ',
        ]);
        Brand::create([
            'title' => 'mashad leather brand',
            'name' => ' چرم مشهد ',
        ]);
        Brand::create([
            'title' => 'aldo brand',
            'name' => '  آلدو ',
        ]);
        Brand::create([
            'title' => 'aldo brand',
            'name' => '  آلدو ',
        ]);
        Brand::create([
            'title' => 'shifer brand',
            'name' => '  شیفر ',
        ]);
        Brand::create([
            'title' => 'crocoleather brand',
            'name' => ' چرم کروکو ',
        ]);
        Brand::create([
            'title' => 'dorsa brand',
            'name' => ' درسا ',
        ]);
        Brand::create([
            'title' => 'berttonix brand',
            'name' => ' برتونیکس ',
        ]);
        Brand::create([
            'title' => 'leather city brand',
            'name' => ' شهر چرم ',
        ]);
        Brand::create([
            'title' => 'lord-b-brand',
            'name' => ' لرد ',
        ]);
        Brand::create([
            'title' => 'swatch brand',
            'name' => ' سواچ ',
        ]);
        Brand::create([
            'title' => 'goodlook brand',
            'name' => ' گودلوک ',
        ]);
        Brand::create([
            'title' => 'li ning brand',
            'name' => ' لینینگ ',
        ]);
        Brand::create([
            'title' => 'panil brand',
            'name' => ' پانیل ',
        ]);
        Brand::create([
            'title' => 'unipro brand',
            'name' => ' یونی پرو ',
        ]);
        Brand::create([
            'title' => 'eloj brand',
            'name' => ' الوج ',
        ]);
    }
}
