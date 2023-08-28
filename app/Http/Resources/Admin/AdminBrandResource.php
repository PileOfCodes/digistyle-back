<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
class AdminBrandResource extends JsonResource
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
            "english" => $this->english,
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description,
            "primary_image" => $this->primary_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->primary_image) : null,
            "brand_image" => $this->brand_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->brand_image) : null,
            "slider_image" => $this->slider_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->slider_image) : null,
            "category_image" => $this->category_image != null ? url(env('ADMIN_BRAND_IMAGE') . $this->category_image) : null,
            "iranian_designer" => $this->iranian_designer,
            "status" => $this->status,
        ];
    }
}
