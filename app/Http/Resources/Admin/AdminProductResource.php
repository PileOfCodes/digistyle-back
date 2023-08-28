<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductResource extends JsonResource
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
            "description" => $this->description,
            "delivery_amount" => $this->delivery_amount,
            "status" => $this->status,
            "primary_image" => url(env('ADMIN_PRODUCT_IMAGE') . $this->primary_image),
            "colors" => ColorResource::collection($this->whenLoaded('colors')),
            "images" => ProductImageResource::collection($this->images),
            "attributes" => ValueResource::collection($this->whenLoaded('attributes',function() {
                return $this->attributes->load('attribute');
            })),
            "category" => new AdminChildCategoryResource($this->whenLoaded('category', function(){
                return $this->category->load('parent');
            })),
        ];
    }
}
