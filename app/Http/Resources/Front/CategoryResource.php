<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\Admin\AdminBrandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "brand_name" => $this->brand_name,
            "parent" => $this->parent_category_id,
            "subCategories" => SubCategoryResource::collection($this->subCategories, function() {
                return $this->subCategories->load('children');
            }),
            "image" => $this->image != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->image) : null,
            "icon" => $this->icon != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->icon) : null,
            "status" => $this->status
        ];
    }
}
