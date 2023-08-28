<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            "name" => $this->name,
            "priority" => $this->priority,
            "attribute_children" => AttributeResource::collection($this->whenLoaded('children')),
            "category_id" => $this->childCategory_id,
            "category" => new AdminChildCategoryResource($this->whenLoaded('category', function() {
                return $this->category->load('parent');
            })),
            "status" => $this->status,
        ];
    }
}
