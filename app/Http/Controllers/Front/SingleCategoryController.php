<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Front\CategoryResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\SingleCategoryResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\ChildCategoryFilter;
use App\Models\Color;
use App\Models\Filter;
use App\Models\ParentCategory;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\SingleCategory;
use App\Models\SingleCategoryFilter;
use App\Models\Size;
use Illuminate\Http\Request;

class SingleCategoryController extends ApiController
{
    public function getAllCategories(Request $request)
    {
        $parent = ParentCategory::where('slug', $request->slug)->first();
        $categories = SingleCategory::where('parent_category_id', $parent->id)->get();
        return $this->succesResponse("get all categories",200, SingleCategoryResource::collection($categories));
    }

    public function getFirstCardProducts(Request $request)
    {
        $parent = ParentCategory::where('slug', $request->slug)->first();
        // $categories = SingleCategory::where('parent_category_id', $parent->id)->get();
        return $this->succesResponse("get first category card info",200, [
            "products" => null,

        ]);

    }

    public function getDetails(Request $request) {
        
        $single = SingleCategory::where('slug', $request->slug)->first();
        $products = Product::filters()->paginate(20);
        return $this->succesResponse('all categories details', 200, [
            'categories' => new SingleCategoryResource($single),
            'products' => SingleProductResource::collection($products),
            'links' => SingleProductResource::collection($products)->response()->getData()->links,
            'meta' => SingleProductResource::collection($products)->response()->getData()->meta,
        ]);
    }

    public function getChildren(Request $request)
    {
        if($request->search != null || $request->search != '')
        {
            $singleCategory = SingleCategory::where('slug', $request->slug)->first();
            $children = ChildCategory::where('singleCategory_id', $singleCategory->id)
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get();
            return $this->succesResponse("all subCategory children", 200, ChildCategoryResource::collection($children));
        }
        if($request->search == null || $request->search == '')
        {
            $singleCategory = SingleCategory::where('slug', $request->slug)->first();
            $children = ChildCategory::where('singleCategory_id', $singleCategory->id)->get();
            return $this->succesResponse("all subCategory children", 200, ChildCategoryResource::collection($children));
        }

    }

    public function getFilters(Request $request)
    {
        $singleCategory = SingleCategory::where('slug', $request->slug)->first();
        $filters = SingleCategoryFilter::where('singleCategory_id', $singleCategory->id)->pluck('filter_id');
        $names = Filter::whereIn('id', $filters)->distinct()->pluck('name');
        return $this->succesResponse("filters", 200, new FilterResource($names));
    }

    public function getSliderChildren(Request $request)
    {
        $singleCategory = SingleCategory::where('slug', $request->slug)->first();
        $children = ChildCategory::where('singleCategory_id', $singleCategory->id)->get();
        return $this->succesResponse("all singleCategory children", 200, ChildCategoryResource::collection($children));
    }

    public function getColors(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colros = Color::whereIn('id', $color_ids)->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colros));
        }else if($request->search != null || $request->search != '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colors = Color::whereIn('id', $color_ids)
            ->where('color_value', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colors));
        }
    }

    public function getBrands(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $brandIds = Product::whereIn('childCategory_id', $child)->pluck('brand_id');
            $brands = Brand::whereIn('id', $brandIds)->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }else if($request->search != null || $request->search != '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $brandIds = Product::whereIn('childCategory_id', $child)->pluck('brand_id');
            $brands = Brand::where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")->whereIn('id', $brandIds)->distinct()->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }
    }


    public function getSizes(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $size_ids = ProductSize::whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)->get();
            return $this->succesResponse("all category sizes", 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '')
        {
            $single = SingleCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $size_ids = ProductSize::whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)
            ->where('size_value', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse("all category sizes", 200, SizeResource::collection($sizes));
        }
    }

    public function womenWatches() {
        $child = ChildCategory::where('slug','category-women-analouge-watches')
        ->orWhere('slug', 'category-women-digital-watches')->pluck('id');
        $products = Product::whereIn('childCategory_id', $child)->get();
        return $this->succesResponse('women watches', 200, SingleProductResource::collection($products));
    }

    public function getMakeup() {
        $child = ChildCategory::where('slug','category-lip-cream')->first();
        $products = Product::where('childCategory_id', $child->id)->get();
        return $this->succesResponse('women watches', 200, SingleProductResource::collection($products));
    }

    public function getUnderwear() {
        $child = ChildCategory::where('slug','category-men-underwear')->first();
        $products = Product::where('childCategory_id', $child->id)->get();
        return $this->succesResponse('women watches', 200, SingleProductResource::collection($products));
    }

    public function getChildCategories(Request $request) {
        $single = SingleCategory::where('slug', $request->slug)->first();
        $child = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
        $categories = Product::whereIn('childCategory_id', $child)->distinct()->pluck('childCategory_id');
        $childCategories = ChildCategory::whereIn('id', $categories)->get();
        return $this->succesResponse('child categories', 200, ChildCategoryResource::collection($childCategories));
    }
}
