<?php

namespace App\Http\Resources\Admin;

use App\Http\Controllers\Admin\AdminParentCategoryController;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
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
            "parent_id" => $this->parent_category_id,
            "parent" => new ParentCategoryResource($this->parent),
            "image" => $this->image != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->image) : null,
            "icon" => $this->icon != null ? url(env("ADMIN_CATEGORY_IMAGE"). $this->icon) : null,
            "status" => $this->status
        ];
    }
}
