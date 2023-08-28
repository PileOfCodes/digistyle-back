<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\AdminProductResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Admin\SliderResource;
use App\Http\Resources\Admin\WeekstyleResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\FavoriteBrandResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\ProductResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Http\Resources\IranianBrandresource;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\ChildCategory;
use App\Models\ChildCategoryFilter;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Filter;
use App\Models\Like;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSeller;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Slider;
use App\Models\Weekstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class landingController extends ApiController
{
    public function searchItems(Request $request)
    {
        if($request->q != null || $request->q != '')
        {
            $categories = ChildCategory::where('title', 'LIKE', "%{$request->q}%")
            ->orWhere('name', 'LIKE', "%{$request->q}%")->take(4)->get();
            $brands = Brand::where('title', 'LIKE', "%{$request->q}%")
            ->orWhere('name', 'LIKE', "%{$request->q}%")->take(4)->get();
            $products = Product::where('title', 'LIKE', "%{$request->q}%")
            ->orWhere('name', 'LIKE', "%{$request->q}%")->take(10)->get();
            return $this->succesResponse('all searched items', 200, [
                "categories" => ChildCategoryResource::collection($categories),
                "brands" => AdminBrandResource::collection($brands),
                "products" => ProductResource::collection($products)
            ]);
        }
    }

    public function iranianDesigners()
    {
        $brands = Brand::orderBy('id')->take(4)->get();
        return $this->succesResponse('landing brand products', 200, [
            "brands" => IranianBrandresource::collection($brands),
        ]);
    }

    public function getAllBrands() {
        $brands = Brand::all();
        return $this->succesResponse('all brands', 200, AdminBrandResource::collection($brands));
    }

    public function getBrands() {
        $brands = Brand::where('is_watch', 1)->get();
        return $this->succesResponse('brands', 200, AdminBrandResource::collection($brands));
    }

    public function getBrandSliders() {
        $collectionIds = Collection::where('is_watch', 1)->pluck('id');
        $sliders = Slider::whereIn('collection_id', $collectionIds)->get();
        return $this->succesResponse('sliders', 200, SliderResource::collection($sliders));
    }

    public function getWatches() {
        $children = ChildCategory::query()->where('slug','category-women-analouge-watches')
        ->orWhere('slug','category-women-digital-watches')
        ->orWhere('slug', 'category-men-watches')->orWhere('slug', 'category-men-digital-watches')->pluck('id');
        $watches = Product::whereIn('childCategory_id', $children)->inRandomOrder()->limit(9)->get();
        return $this->succesResponse('watches', 200, SingleProductResource::collection($watches));
    }

    public function discountedWatches() {
        $children = ChildCategory::query()->where('slug','category-women-analouge-watches')
        ->orWhere('slug','category-women-digital-watches')
        ->orWhere('slug', 'category-men-watches')->orWhere('slug', 'category-men-digital-watches')->pluck('id');
        $proIds = ProductSeller::where('discount_id', '!=', null)->pluck('product_id');
        $watches = Product::whereIn('childCategory_id', $children)->whereIn('id', $proIds)->take(9)->get();
        return $this->succesResponse('watches', 200, SingleProductResource::collection($watches));
    }

    public function getWeekstyle() {
        $ws = Weekstyle::where('is_watch', 1)->first();
        return $this->succesResponse('weekstyle', 200, new WeekstyleResource($ws));
    }

    public function weekstyleProducts() {
        $ws = Weekstyle::where('is_watch', 0)->first();
        return $this->succesResponse('weekstyle', 200, new WeekstyleResource($ws));
    }

    public function getShegeft() {
        $children = ChildCategory::query()->where('slug','category-women-analouge-watches')
        ->orWhere('slug','category-women-digital-watches')
        ->orWhere('slug', 'category-men-watches')->orWhere('slug', 'category-men-digital-watches')->pluck('id');
        $proIds = ProductSeller::where('discount_id', '!=', null)->pluck('product_id');
        $watches = Product::whereIn('childCategory_id', $children)->whereIn('id', $proIds)->take(9)->get();
        return $this->succesResponse('watches', 200, SingleProductResource::collection($watches));
    }

    public function allBrands(Request $request) {
        if($request->search == null || $request->search == '') {
            return $this->succesResponse('brands', 200, AdminBrandResource::collection(Brand::all()));
        }else {
            $brands = Brand::where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('english','LIKE',"%{$request->search}%")->get();
            return $this->succesResponse('brands', 200, AdminBrandResource::collection($brands));
        }
    }

    public function brandCategories(Request $request) {
        $brand = Brand::where('slug', $request->slug)->first();
        $categoryIds = BrandCategory::where('brand_id', $brand->id)->pluck('category_id');
        $categories = ChildCategory::whereIn('id', $categoryIds)->get();
        return $this->succesResponse('brand categories', 200, ChildCategoryResource::collection($categories));
    }

    public function getFilters(Request $request) {
        $brand = Brand::where('slug', $request->slug)->first();
        $categoryIds = BrandCategory::where('brand_id', $brand->id)->pluck('category_id');
        $filterIds = ChildCategoryFilter::whereIn('childCategory_id', $categoryIds)->pluck('filter_id');
        $filters = Filter::whereIn('id', $filterIds)->get();
        return $this->succesResponse('brand categories', 200, FilterResource::collection($filters));
    }
    
    public function getDetails(Request $request) {
        $brand = Brand::where('slug', $request->slug)->first();
        $products = Product::where('brand_id', $brand->id)->paginate(20);
        return $this->succesResponse('brand details', 200, [
            'brand' => new AdminBrandResource($brand),
            'products' => SingleProductResource::collection($products),
            'links' => SingleProductResource::collection($products)->response()->getData()->links,
            'meta' => SingleProductResource::collection($products)->response()->getData()->meta
        ]);
    }

    public function getColors(Request $request) {
        if($request->search == null || $request->search == '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $productIds = Product::where('brand_id', $brand->id)->where('property', 'color')->pluck('id');
            $colorIds = ProductColor::whereIn('product_id', $productIds)->pluck('color_id');
            $colors = Color::whereIn('id', $colorIds)->distinct()->get();
            return $this->succesResponse('brand colors', 200, ColorResource::collection($colors));
        }else if($request->search != null || $request->search != '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $productIds = Product::where('brand_id', $brand->id)->where('property', 'color')->pluck('id');
            $colorIds = ProductColor::whereIn('product_id', $productIds)->pluck('color_id');
            $colors = Color::whereIn('id', $colorIds)
            ->where('name', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('brand colors', 200, ColorResource::collection($colors));

        }
    }

    public function getSizes(Request $request) {
        if($request->search == null || $request->search == '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $productIds = Product::where('brand_id', $brand->id)->where('property', 'size')->pluck('id');
            $sizeIds = ProductSize::whereIn('product_id', $productIds)->pluck('size_id');
            $sizes = Size::whereIn('id', $sizeIds)->distinct()->get();
            return $this->succesResponse('brand sizes', 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $productIds = Product::where('brand_id', $brand->id)->where('property', 'size')->pluck('id');
            $sizeIds = ProductSize::whereIn('product_id', $productIds)->pluck('size_id');
            $sizes = Size::whereIn('id', $sizeIds)
            ->where('size_value', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('brand sizes', 200, SizeResource::collection($sizes));
        }
    }

    public function getChildCategories(Request $request) {
        if($request->search == null || $request->search == '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $childIds = Product::where('brand_id', $brand->id)->pluck('childCategory_id');
            $children = ChildCategory::whereIn('id', $childIds)->get();
            return $this->succesResponse('brand categories', 200, ChildCategoryResource::collection($children));
        }else if($request->search != null || $request->search != '') {
            $brand = Brand::where('slug', $request->slug)->first();
            $childIds = Product::where('brand_id', $brand->id)->pluck('childCategory_id');
            $children = ChildCategory::whereIn('id', $childIds)
            ->where('name', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('brand categories', 200, ChildCategoryResource::collection($children));
        }
    }

    public function getPaidarProducts() {
        $brand = Brand::where('slug', 'pepa-brand')->first();
        $products = Product::where('brand_id', $brand->id)->get();
        return $this->succesResponse('paidar fashion products', 200, SingleProductResource::collection($products));
    }

    public function getProducts() {
        $proIds = ProductSeller::where('discount_id', '!=', null)->pluck('product_id');
        $products = Product::whereIn('id', $proIds)->get();
        return $this->succesResponse('promotion products', 200, SingleProductResource::collection($products));
    }

    public function fbrands() {
        $brands = Brand::where('is_favorite', 1)->limit(2)->get();
        return $this->succesResponse('fovorite brands', 200, FavoriteBrandResource::collection($brands));
    }
}
