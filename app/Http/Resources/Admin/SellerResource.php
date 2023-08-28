<?php

namespace App\Http\Resources\Admin;

use App\Models\Discount;
use App\Models\Warranty;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount = Discount::where('id', optional($this->pivot)->discount_id)->first();
        $warrant = Warranty::where('id', optional($this->pivot)->warrant_id)->first();
        return [
            'name' => $this->name,
            'code' => $this->code,
            'slug' => $this->slug,
            'membership_time' => verta($this->membership_time)->formatDifference(),
            "price" => optional($this->pivot)->price,
            "selected" => $this->selected,
            "discount" => $discount ? new DiscountResource($discount) : null,
            "warrant" => $warrant ? new WarrantResource($warrant) : null ,
            "sending_time" => optional($this->pivot)->sending_time
        ];
    }
}
