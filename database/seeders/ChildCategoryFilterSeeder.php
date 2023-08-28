<?php

namespace Database\Seeders;

use App\Models\ChildCategoryFilter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChildCategoryFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filters = [1,2,5];
        for ($i=62; $i < 247; $i++) { 
            foreach ($filters as $key => $value) {
                ChildCategoryFilter::create([
                    'childCategory_id' => $i,
                    'filter_id' => $value
                ]);
            }
        }
  
    }
}
