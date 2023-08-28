<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildCategoryResource extends JsonResource
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
            "bread" => $this->breadName,
            "slug" => $this->slug,
            "image" => $this->image != null ? url(env('ADMIN_CATEGORY_IMAGE') . $this->image) : null,
            "subCategory_id" => $this->subCategory_id,
            "subCategory" => $this->parent->load('category.parent'),
            "rootParent" => $this->parent->load('category.parent.parent'),
            "status" => $this->status
        ];
    }
}
