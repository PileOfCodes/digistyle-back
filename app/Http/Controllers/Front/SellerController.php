<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\SellerResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Models\Brand;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSeller;
use App\Models\ProductSize;
use App\Models\Seller;
use App\Models\Size;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function getProducts(Request $request)
    {
        $seller = Seller::where('slug', $request->slug)->first();
        $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
        $products = Product::whereIn('id', $productsIds)->get();
        return $this->succesResponse('seller products', 200, SingleProductResource::collection($products));
    }

    public function getSizes(Request $request) {
        if($request->search == null || $request->search == '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $sizesIds = ProductSize::whereIn('product_id', $productsIds)->pluck('size_id');
            $sizes = Size::whereIn('id', $sizesIds)->get();
            return $this->succesResponse('seller sizes', 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $sizesIds = ProductSize::whereIn('product_id', $productsIds)->pluck('size_id');
            $sizes = Size::whereIn('id', $sizesIds)
            ->where('size_value', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('seller sizes', 200, SizeResource::collection($sizes));
        }
    }

    public function getColors(Request $request) {
        if($request->search == null || $request->search == '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $colorsIds = ProductColor::whereIn('product_id', $productsIds)->pluck('color_id'); 
            $colors = Color::whereIn('id', $colorsIds)->get();
            return $this->succesResponse('seller colors', 200, ColorResource::collection($colors));
        }else if($request->search != null || $request->search != '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $colorsIds = ProductColor::whereIn('product_id', $productsIds)->pluck('color_id'); 
            $colors = Color::whereIn('id', $colorsIds)
            ->where('name', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('seller colors', 200, ColorResource::collection($colors));
        }
    }

    public function getCategories(Request $request) {
        if($request->search == null || $request->search == '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $childIds = Product::whereIn('id', $productsIds)->pluck('childCategory_id'); 
            $categories = ChildCategory::whereIn('id', $childIds)->get();
            return $this->succesResponse('seller categories', 200, ChildCategoryResource::collection($categories));
        }else if($request->search != null || $request->search != '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $childIds = Product::whereIn('id', $productsIds)->pluck('childCategory_id'); 
            $categories = ChildCategory::whereIn('id', $childIds)
            ->where('name', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('seller categories', 200, ChildCategoryResource::collection($categories));
        }
    }

    public function getBrands(Request $request) {
        if($request->search == null || $request->search == '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $brandIds = Product::whereIn('id', $productsIds)->pluck('brand_id'); 
            $brands = Brand::whereIn('id', $brandIds)->get();
            return $this->succesResponse('seller brands', 200, AdminBrandResource::collection($brands));
        }else if($request->search != null || $request->search != '') {
            $seller = Seller::where('slug', $request->slug)->first();
            $productsIds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id');
            $brandIds = Product::whereIn('id', $productsIds)->pluck('brand_id'); 
            $brands = Brand::whereIn('id', $brandIds)
            ->where('name', 'LIKE', "%{$request->search}%")->distinct()->get();
            return $this->succesResponse('seller brands', 200, AdminBrandResource::collection($brands));
        }
    }

    public function getSeller(Request $request) {
        $seller = Seller::where('slug', $request->slug)->first();
        return $this->succesResponse('seller', 200, new SellerResource($seller));
    }
}
