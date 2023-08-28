<?php

namespace App\Http\Resources;

use App\Http\Resources\Front\SingleProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = Product::where('id', $this->product_id)->first();
        $user = User::where('id', $this->user_id)->first();
        return [
            'user_id' => $user->id,
            'product' => new SingleProductResource($product),
            'isLiked' => $this->isLiked
        ];
    }
}
