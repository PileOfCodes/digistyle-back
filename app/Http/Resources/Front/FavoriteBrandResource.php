<?php

namespace App\Http\Resources\Front;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = Product::where('brand_id', $this->id)->distinct()->limit(3)->get();
        return [
            "id" => $this->id,
            "title" => $this->title,
            "english" => $this->english,
            "slug" => $this->slug,
            "name" => $this->name,
            "brand_image" => $this->brand_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->brand_image) : null,
            'products' => SingleProductResource::collection($products)
        ];
    }
}
