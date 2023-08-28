<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Front\SingleProductResource;
use App\Models\Product;
use App\Models\ProductWeekstyle;
use Illuminate\Http\Resources\Json\JsonResource;

class WeekstyleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $proIds = ProductWeekstyle::where('weekstyle_id', $this->id)->pluck('product_id');
        $products = Product::whereIn('id', $proIds)->get();
        return [
            'id' => $this->id,
            'image' => url(env('ADMIN_WEEKSTYLE_IMAGE') . $this->image),
            'is_watch' => $this->is_watch,
            'products' => SingleProductResource::collection($products)
        ];
    }
}
