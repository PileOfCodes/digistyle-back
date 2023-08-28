<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\DiscountResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductSeller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShegeftController extends ApiController
{
    public function discounted()
    {
        $discountIds = Discount::orderBy('percent','desc')->where('expired_at', '>', Carbon::now())->inRandomOrder()->limit(9)->pluck('id');
        $ids = ProductSeller::query()->whereIn('discount_id', $discountIds)
        ->pluck('product_id');
        $products = Product::whereIn('id', $ids)->get();
        return $this->succesResponse('discounted products', 200, [
        'products' => SingleProductResource::collection($products)
        ]);
    }
}
