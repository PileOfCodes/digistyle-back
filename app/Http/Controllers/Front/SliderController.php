<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SliderResource;
use App\Models\Collection;
use App\Models\ParentCategory;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends ApiController
{
    public function getSlider()
    {
        $sliders = Slider::query()->whereRelation('collection', 'parent_category_id', null)
        ->whereRelation('collection', 'is_discounted', 0)
        ->whereRelation('collection', 'is_watch', 0)->get();
        return $this->succesResponse("slider content", 200, SliderResource::collection($sliders->load('collection')));
    }

    public function getCategorySlider(Request $request)
    {
        $parent = ParentCategory::where('slug', $request->slug)->first();
        $sliders = Slider::query()->whereRelation('collection', 'parent_category_id', $parent->id)->get();
        return $this->succesResponse("slider content", 200, SliderResource::collection($sliders->load('collection')));
    }
}
