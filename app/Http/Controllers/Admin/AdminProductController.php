<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminProductResource;
use App\Models\AttributeValueProduct;
use App\Models\CollectionProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSeller;
use App\Models\ProductSize;
use App\Models\ProductWeight;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminProductController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $product = Product::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->orWhere('description', 'LIKE', "%{$request->search}%")
            ->orWhere('details', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $product = Product::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("admin products",200, [
            "products" => AdminProductResource::collection($product),
            "links" => AdminProductResource::collection($product)->response()->getData()->links,
            "meta" => AdminProductResource::collection($product)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3","unique:products,title"],
            "name" => "required",
            "primary_image" => "required|image",
            "images.*" => "nullable|image",
            "attributes.*" => "nullable",
            "colors.*" => "nullable",
            "sizes.*" => "nullable",
            "sellers.*" => "required",
            "sellers.*.price" => "required",
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
      
        $imageName = Carbon::now()->microsecond .'.'. $request->primary_image->extension();
        $request->primary_image->storeAs('images/products',$imageName,'public');
        DB::beginTransaction();

        if($request->has('images')) {
            $productImages = [];
            foreach($request->images as $productImage)
            {
                $image = Carbon::now()->microsecond .'.'. $productImage->extension();
                $productImage->storeAs('images/products',$image,'public');
                array_push($productImages, $image);
            }
        }
        $product = Product::create([
            "title" => $request->title,
            "name" => $request->name,
            "sku" => mt_rand(1000000,9999999),
            "delivery_amount" => $request->delivery_amount ?? 0,
            "childCategory_id" => $request->childCategory_id ?? null,
            "primary_image" => $imageName,
            "brand_id" => $request->brand_id,
            "property" => $request->property ?? null,
            "status" => $request->has('status') ? $request->status : 1,
        ]);

        if($request->has('images'))
        {
            foreach ($productImages as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    "image" => $img
                ]);
            }
        }

        if($request->has('collection')) {
            foreach ($request->collection as $coll) {
                CollectionProduct::create([
                    'product_id' => $product->id,
                    'collection_id' => $coll['collection_id']
                ]);
            }
        }

        if($product->property == 'color' && $request->has('colors'))
        {
            foreach ($request->colors as $color) {
                ProductColor::create([
                    "product_id" => $product->id,
                    "color_id" => $color['color_id'],
                    'quantity' => $color['quantity']
                ]);
            }
        }

        if($product->property == 'weight' && $request->has('weights'))
        {
            foreach ($request->weights as $weight) {
                ProductWeight::create([
                    "product_id" => $product->id,
                    "weight_id" => $weight['weight_id'],
                    'quantity' => $weight['quantity']
                ]);
            }
        }

        if($request->has('attributes'))
        {
            foreach ($request->attributes as $attribute_value) {
                AttributeValueProduct::create([
                    "product_id" => $product->id,
                    "attribute_value_id" => $attribute_value
                ]);
            }
        }
        
        if($product->property == 'size' && $request->has('sizes'))
        {
            foreach ($request->sizes as $size) {
                ProductSize::create([
                    "product_id" => $product->id,
                    "size_id" => $size['size_id'],
                    'quantity' => $size['quantity']
                ]);
            }
        }

        if($request->has('sellers'))
        {
            foreach ($request->sellers as $seller) {
                ProductSeller::create([
                    "product_id" => $product->id,
                    "seller_id" => $seller['seller_id'],
                    "discount_id" => $seller['discount_id'] ?? null,
                    'warrant_id' => $seller['warrant_id'],
                    'sending_time' => $seller['sending_time'],
                    'price' => $seller['price']
                ]);
            }
        }
    
        DB::commit();
        return $this->succesResponse(
            "product created successfully",
            201, 
            new AdminProductResource($product)
        );
    }

    public function show(Product $product)
    {
        return $this->succesResponse(
            "product $product->title",
            200, 
            new AdminProductResource(
                $product->load('images')->load('category')
                ->load('attributes')->load('colors'))
            );
    }

    public function update(Request $request, Product $product)
    {
        // $validator = Validator::make($request->all(),[
        //     "title" => ["required","min:3",Rule::unique('products')->ignore($product->id)],
        //     "name" => "required",
        //     "primary_image" => "nullable|image",
        //     "images.*" => "nullable|image",
        // ]);
        // if($validator->fails()) {
        //     return $this->errorResponse($validator->messages(),422);
        // }
        
        if($request->has('primary_image'))
        {
            $imageName = Carbon::now()->microsecond .'.'. $request->primary_image->extension();
            $request->primary_image->storeAs('images/products',$imageName,'public');
        }
        DB::beginTransaction();

        if($request->has('images')) {
            $productImages = [];
            foreach($request->images as $productImage)
            {
                $image = Carbon::now()->microsecond .'.'. $productImage->extension();
                $productImage->storeAs('images/products',$image,'public');
                array_push($productImages, $image);
            }
        }
        $product->update([
            // "title" => $request->title,
            // "name" => $request->name,
            // "price" => $request->price,
            "delivery_amount" => $request->delivery_amount ?? $product->delivery_amount,
            "childCategory_id" => $request->childCategory_id ?? $product->childCategory_id,
            "primary_image" => $imageName ?? $product->primary_image,
            "status" => $request->has('status') ? $request->status : $product->status,
        ]);

        if($request->has('images'))
        {
            foreach($product->images as $proImage)
            {
                $proImage->delete();
            }
            foreach ($productImages as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    "image" => $img
                ]);
            }
        }
        DB::commit();
        return $this->succesResponse(
            "product updated successfully",
            200, 
            new AdminProductResource($product)
        );
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        $product->delete();
        DB::commit();
        return $this->errorResponse(
            "product deleted successfully", 
            200, 
            new AdminProductResource($product)
        );
    }
}
