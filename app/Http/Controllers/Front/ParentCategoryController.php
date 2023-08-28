<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\ParentCategoryResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Http\Resources\Front\SubCategoryResource;
use App\Models\Brand;
use App\Models\ChildCategory;
use App\Models\ChildCategoryFilter;
use App\Models\Color;
use App\Models\Filter;
use App\Models\ParentCategory;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\SubCategoryFilter;
use Illuminate\Http\Request;

class ParentCategoryController extends ApiController
{
    public function allParentCategories()
    {
        $parentCategories = ParentCategory::where([
            ['title','!=', 'category-apparel'],
            ['title','!=', 'category-uni-clothing']
        ])->get();
        return $this->succesResponse("all parent categories", 200, ParentCategoryResource::collection($parentCategories->load('categories')));
    }

    public function parentCategories()
    {
        $parentCategories = ParentCategory::where([
            ['title','!=', 'category-apparel'],
            ['title','!=', 'category-uni-clothing']
        ])->get();
        return $this->succesResponse("all parent categories", 200, ParentCategoryResource::collection($parentCategories->load('singleCategories')));
    }

    public function getDetails(Request $request) {
        $sub = SubCategory::where('slug', $request->slug)->first();
        $products = Product::filters()->paginate(20);
        return $this->succesResponse('all categories details', 200, [
            'categories' => new SubCategoryResource($sub),
            'products' => SingleProductResource::collection($products),
            'links' => SingleProductResource::collection($products)->response()->getData()->links,
            'meta' => SingleProductResource::collection($products)->response()->getData()->meta,
        ]);
    }

    public function getChildren(Request $request)
    {
        if($request->search != null || $request->search != '')
        {
            $subCategory = SubCategory::where('slug', $request->slug)->first();
            $children = ChildCategory::where('subCategory_id', $subCategory->id)
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get();
            return $this->succesResponse("all subCategory children", 200, ChildCategoryResource::collection($children));
        }
        if($request->search == null || $request->search == '')
        {
            $subCategory = SubCategory::where('slug', $request->slug)->first();
            $children = ChildCategory::where('subCategory_id', $subCategory->id)->get();
            return $this->succesResponse("all subCategory children", 200, ChildCategoryResource::collection($children));
        }

    }

    public function getFilters(Request $request)
    {
        $subCategory = SubCategory::where('slug', $request->slug)->first();
        $filters = SubCategoryFilter::where('subCategory_id', $subCategory->id)->pluck('filter_id');
        $names = Filter::whereIn('id', $filters)->distinct()->pluck('name');
        return $this->succesResponse("filters", 200, new FilterResource($names));
    }

    public function getCategories(Request $request)
    {
        $subCategory = SubCategory::where('slug', $request->slug)->first();
        $child = ChildCategory::where('subCategory_id', $subCategory->id)->pluck('id');
        $categories = Product::whereIn('childCategory_id', $child)->distinct()->pluck('childCategory_id');
        $childCategories = ChildCategory::whereIn('id', $categories)->get();
        return $this->succesResponse('child categories', 200, ChildCategoryResource::collection($childCategories));
    }

    public function getSliderChildren(Request $request)
    {
        $subCategory = SubCategory::where('slug', $request->slug)->first();
        $children = ChildCategory::where('subCategory_id', $subCategory->id)->get();
        return $this->succesResponse("all subCategory children", 200, ChildCategoryResource::collection($children));
    }

    public function getColors(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colros = Color::whereIn('id', $color_ids)->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colros));
        }else if($request->search != null || $request->search != '')
        {
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
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
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
            $brandIds = Product::whereIn('childCategory_id', $child)->pluck('brand_id');
            $brands = Brand::whereIn('id', $brandIds)->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }else if($request->search != null || $request->search != '')
        {
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
            $brandIds = Product::whereIn('childCategory_id', $child)->pluck('brand_id');
            $brands = Brand::whereIn('id', $brandIds)
            ->where('title', 'LIKE', "%{$request->title}%")
            ->orWhere('name', 'LIKE', "%{$request->name}%")->distinct()->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }
    }


    public function getSizes(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $child)->pluck('id');
            $size_ids = ProductSize::whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)->get();
            return $this->succesResponse("all category sizes", 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '')
        {
            $sub = SubCategory::where('slug', $request->slug)->first();
            $child = ChildCategory::where('subCategory_id', $sub->id)->pluck('id');
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
}
