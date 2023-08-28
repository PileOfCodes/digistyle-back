<?php

namespace App\Http\Controllers;

use App\Http\Resources\Front\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class MostVisitedController extends ApiController
{
    public function mostVisited()
    {
        $products = Product::orderBy('visit', 'desc')->inRandomOrder()->limit(9)->get();
        return $this->succesResponse('most visited products', 200, [
            "products" => SingleProductResource::collection($products)
        ]);
    }

    public function mostSells()
    {
        $products = Product::where('sell_count', '>', 0)->orderBy('sell_count', 'desc')
        ->inRandomOrder()->limit(9)->get();
        return $this->succesResponse('most sold products', 200, [
            "products" => SingleProductResource::collection($products)
        ]);
    }

    public function newest()
    {
        $products = Product::orderBy('created_at', 'desc')->inRandomOrder()->limit(9)->get();
        return $this->succesResponse('newest products', 200, [
            "products" => SingleProductResource::collection($products)
        ]);
    }
}
