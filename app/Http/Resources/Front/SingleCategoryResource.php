<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\Admin\ParentCategoryResource;
use App\Models\ChildCategory;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $total = 0;
        $children = ChildCategory::where('singleCategory_id', $this->id)->withCount('products')->get();
        foreach ($children as $child) {
            $total += $child->products_count;
        }      
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "name" => $this->name,
            "parent" => new ParentCategoryResource($this->parent),
            "children" => ChildCategoryResource::collection($this->children),
            "image" => $this->image != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->image) : null,
            "primary_image" => $this->primary_image != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->primary_image) : null,
            'product_count' => $total
        ];
    }
}
