<?php

namespace App\Http\Resources;

use App\Http\Resources\Front\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class IranianBrandresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = [];
        $brandproducts = Product::where('brand_id', $this->id)->orderBy('id')->take(4)->get();
        foreach ($brandproducts as $product) {
            array_push($products, $product);
        }
        $products = array_unique($products);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "name" => $this->name,
            "category_image" => $this->category_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->category_image) : null,
            "iranian_designer" => $this->iranian_designer,
            "products" => ProductResource::collection($products)
        ];
    }
}
