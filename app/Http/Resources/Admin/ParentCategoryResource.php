<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Front\CategoryResource;
use App\Http\Resources\Front\SingleCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentCategoryResource extends JsonResource
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
            "slug" => $this->slug,
            "name" => $this->name,
            "status" => $this->status,
            "categories" => CategoryResource::collection($this->whenLoaded('categories')),
            "singleCategories" => SingleCategoryResource::collection($this->whenLoaded('singleCategories'))
        ];
    }
}
