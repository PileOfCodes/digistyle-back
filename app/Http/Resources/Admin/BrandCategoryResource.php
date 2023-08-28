<?php

namespace App\Http\Resources\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $brand = Brand::where('id', $this->brand_id)->first();
        $category = SubCategory::where('category_id', $this->category_id)->first();
        return [
            "id" => $this->id,
            "slug" => $brand->slug,
            "category_slug" => $category->slug,
            "brand_id" => $this->brand_id,
            "category_id" => $this->category_id,
            "image" => url(env('ADMIN_BRAND_IMAGE') . $this->image)
        ];
    }
}
