<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BrandCategoryResource;
use App\Models\BrandCategory;
use Illuminate\Http\Request;

class BrandCategoryController extends ApiController
{
    public function allBrandCategories()
    {
        $brandCategories = BrandCategory::all();
        return $this->succesResponse(
            "all brand categories", 
            200, 
            BrandCategoryResource::collection($brandCategories)
        );
    }
}
