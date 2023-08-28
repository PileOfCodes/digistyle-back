<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\AdminSubCategoryResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Http\Resources\Front\SubCategoryResource;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\ChildCategoryFilter;
use App\Models\Color;
use App\Models\Filter;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends ApiController
{
    // 
}
