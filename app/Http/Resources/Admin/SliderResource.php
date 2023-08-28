<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Front\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            "image" => url(env('ADMIN_SLIDER_IMAGE'). $this->image),
            "collection_id" => $this->collection_id,
            "collection" => new CollectionResource($this->collection),
        ];
    }
}
