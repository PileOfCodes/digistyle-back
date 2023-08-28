<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Http\Resources\Front\UserResource;
use App\Http\Resources\LikeResource;
use App\Models\ChildCategory;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SingleProductController extends ApiController
{
    public function getSingleProduct(Request $request)
    {
        $product = Product::where('slug', $request->slug)->first();
        $product->update(['visit'=> $product->visit + 1]);
        return $this->succesResponse("single product childCategory", 200, new SingleProductResource($product));
    }

    public function singleProductCategory(Request $request)
    {
        $product = Product::where('slug', $request->slug)->first();
        $child = ChildCategory::where('id', $product->childCategory_id)->first();
        return $this->succesResponse("single product childCategory", 200, new ChildCategoryResource($child));
    }

    public function getOtherProductDesigns(Request $request)
    {
        $products = Product::where([['slug', '!=', $request->slug], ['name', '=' ,$request->name]])->get();
        return $this->succesResponse("other product desings", 200, SingleProductResource::collection($products));
    }

    public function updateLike(Request $request)
    {
        $product = Product::where('slug', $request->slug)->first();
        $like = Like::query()->firstOrCreate([
            'user_id' => Auth::user()->id,
            'product_id' => $product->id
        ]);
        $like->update(['isLiked' => !$like->isLiked]);
        return $this->succesResponse("product's like is updated",200, new LikeResource($like));
    }

    public function unLike(Request $request)
    {
        $likes = Like::where('user_id', $request->userId)->get();
        foreach ($likes as $like) {
            $like->update(['isLiked' => !$like->isLiked]);
        }
        return $this->succesResponse("product's like is updated",200, []);
    }
}
