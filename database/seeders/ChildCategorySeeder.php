<?php

namespace Database\Seeders;

use App\Models\ChildCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChildCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChildCategory::create([
            'title' => 'category-women-tee-shirts',
            'name' => ' تیشرت ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-polo-shirt',
            'name' => ' پلو شرت ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-homewear',
            'name' => ' لباس خواب و راحتی زنانه ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-manteau-and-poncho',
            'name' => ' مانتو، پانچو و رویه ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-shirt',
            'name' => ' شومیز ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-blouse',
            'name' => ' بلوز ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-tops-and-croptops',
            'name' => ' تاپ و نیم تنه  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-tunic',
            'name' => '  تونیک  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-sweatshirts',
            'name' => '  سویشرت  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-hoodies',
            'name' => '  هودی  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-knitwear',
            'name' => '  ژاکت و پلیور  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-trousers-and-jumpsuits',
            'name' => '  شلوار و سرهمی  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-jeans',
            'name' => ' جین ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-skirts',
            'name' => ' دامن  ',
            'subCategory_id' => 1
        ]);
        ChildCategory::create([
            'title' => 'category-women-underwear',
            'name' => ' لباس زیر  ',
            'subCategory_id' => 1
        ]);
        // purse
        ///////////////

        
        ChildCategory::create([
            'title' => 'category-women-bags',
            'name' => ' کیف زنانه  ',
            'subCategory_id' => 2
        ]);
        ChildCategory::create([
            'title' => 'category-women-wallets-and-cosmetic-bags',
            'name' => ' کیف پول و لوازم آرایش  ',
            'subCategory_id' => 2
        ]);
        ChildCategory::create([
            'title' => 'category-women-backpacks',
            'name' => ' کوله پشتی  ',
            'subCategory_id' => 2
        ]);
        ChildCategory::create([
            'title' => 'category-women-gifts-and-sets',
            'name' => '  ست هدیه  ',
            'subCategory_id' => 2
        ]);
        ChildCategory::create([
            'title' => 'category-trolley-case-and-luggage',
            'name' => '  کیف سفری و چمدان  ',
            'subCategory_id' => 2
        ]);
        // shoes
        ////////////
        ChildCategory::create([
            'title' => 'category-casual-shoes-for-women',
            'name' => ' کفش روزمره  ',
            'subCategory_id' => 3
        ]);
        ChildCategory::create([
            'title' => 'category-women-flat-shoes',
            'name' => ' کفش تخت  ',
            'subCategory_id' => 3
        ]);
        ChildCategory::create([
            'title' => 'category-women-heeled-shoes',
            'name' => ' کفش پاشنه دار  ',
            'subCategory_id' => 3
        ]);
        ChildCategory::create([
            'title' => 'category-women-sport-shoes',
            'name' => ' کفش ورزشی  ',
            'subCategory_id' => 3
        ]);
        // accessory
        /////////////////
        ChildCategory::create([
            'title' => 'category-women-eyewear',
            'name' => ' عینک ',
            'subCategory_id' => 4
        ]);
        ChildCategory::create([
            'title' => 'category-women-scarves',
            'name' => '  شال و روسری  ',
            'subCategory_id' => 4
        ]);
        ChildCategory::create([
            'title' => 'category-women-analouge-watches',
            'name' => ' ساعت عقربه ای ',
            'subCategory_id' => 5
        ]);
        ChildCategory::create([
            'title' => 'category-women-digital-watches',
            'name' => '   ساعت دیجیتال  ',
            'subCategory_id' => 5
        ]);
        ChildCategory::create([
            'title' => 'category-women-gold-jewelry',
            'name' => '  زیورآلات طلا ',
            'subCategory_id' => 6
        ]);
        ChildCategory::create([
            'title' => 'category-women-silver-jewelry',
            'name' => '  زیورآلات نقره ',
            'subCategory_id' => 6
        ]);
    }
}
