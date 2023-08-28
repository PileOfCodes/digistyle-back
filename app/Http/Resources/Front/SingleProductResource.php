<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\AdminChildCategoryResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\ProductImageResource;
use App\Http\Resources\Admin\SellerResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Admin\ValueResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "name" => $this->name,
            "slug" => $this->slug,
            "sku" => $this->sku,
            "property" => $this->property,
            "brand" => new AdminBrandResource($this->brand),
            "sellers" => SellerResource::collection($this->sellers),
            "sizes" => SizeResource::collection($this->sizes),
            "delivery_amount" => $this->delivery_amount,
            "status" => $this->status,
            "order_count" => $this->order_count,
            "primary_image" => url(env('ADMIN_PRODUCT_IMAGE') . $this->primary_image),
            "colors" => ColorResource::collection($this->colors),
            "weights" => $this->weights,
            "images" => ProductImageResource::collection($this->images),
            "attributes" => AttributeValueResource::collection($this->attributes),
            "category" => new AdminChildCategoryResource($this->whenLoaded('category', function(){
                return $this->category->load('parent');
            })),
        ];
    }
}
