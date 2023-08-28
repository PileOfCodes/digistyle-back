<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ParentCategoryResource;
use App\Http\Resources\Admin\SliderResource;
use App\Http\Resources\Front\SingleCategoryResource;
use App\Models\Collection;
use App\Models\ParentCategory;
use App\Models\SingleCategory;
use App\Models\Slider;
use Illuminate\Http\Request;

class DiscountController extends ApiController
{
    public function getCollection() {
        $collection = Collection::where('is_discounted', 1)->pluck('id');
        $sliders = Slider::whereIn('collection_id', $collection)->get();
        return $this->succesResponse('discounted sliders', 200, SliderResource::collection($sliders));
    }

    public function getCategories()
    {
        $parent = ParentCategory::where([
            ['title','!=', 'category-apparel'],
            ['title','!=', 'category-uni-clothing']
        ])->get();
        return $this->succesResponse("get all categories",200, ParentCategoryResource::collection($parent->load('singleCategories')));
    }
}
