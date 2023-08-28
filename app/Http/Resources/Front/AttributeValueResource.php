<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\Admin\AttributeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
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
            "parent_attribute" => new AttributeResource($this->attribute),
            "status" => $this->status
        ];
    }
}
