<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TrashedChildCategoryResource extends JsonResource
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
            "image" => $this->image !== null ? url(env("ADMIN_CHILDCATEGORY_IMAGE"). $this->image) : null,
            "status" => $this->status,
            "parent" => $this->subCategory_id
        ];
    }
}
