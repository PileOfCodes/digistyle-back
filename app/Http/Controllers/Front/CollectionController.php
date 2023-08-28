<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\ProductResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Models\BrandCollection;
use App\Models\ChildCategory;
use App\Models\ChildCategoryCollection;
use App\Models\ChildCategoryFilter;
use App\Models\Collection;
use App\Models\CollectionProduct;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductWeight;
use App\Models\Size;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends ApiController
{
    public function getProducts(Request $request)
    {
        $collection = Collection::where('slug', $request->slug)->first();
        $products = Product::filters()->paginate(20);
        return $this->succesResponse('all products', 200, [
            'collection' => $collection,
            'products' => SingleProductResource::collection($products),
            "links" => SingleProductResource::collection($products)->response()->getData()->links,
            "meta" => SingleProductResource::collection($products)->response()->getData()->meta
        ]);
    }

    public function getFilters(Request $request)
    {
        $coll = Collection::where('slug', $request->slug)->first();
        $childIds = ChildCategoryCollection::where('collection_id', $coll->id)->pluck('child_category_id');
        $filters = ChildCategoryFilter::whereIn('childCategory_id', $childIds)->pluck('filter_id');
        $names = DB::table('filters')->whereIn('id', $filters)->pluck('name');
        return $this->succesResponse("child category filters", 200, new FilterResource($names));
    }

    public function allColors(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colros = Color::whereIn('id', $color_ids)->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colros));
        }else if($request->search != null || $request->search != '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colors = Color::whereIn('id', $color_ids)
            ->where('color_value', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colors));
        }
    }

    public function allSizes(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $size_ids = ProductSize::whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)->distinct()->get();
            return $this->succesResponse('sizes', 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $size_ids = ProductSize::whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)
            ->where('size_value', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse('sizes', 200, SizeResource::collection($sizes));
        }
    }

    public function allWeights(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $weight_ids = ProductWeight::whereIn('product_id', $products)->pluck('weight_id');
            $weights = Weight::whereIn('id', $weight_ids)->distinct()->get();
            return $weights;
        }else if($request->search != null || $request->search != '')
        {
            $coll = Collection::where('slug', $request->slug)->first();
            $products = CollectionProduct::where('collection_id', $coll->id)->pluck('product_id');
            $weight_ids = ProductWeight::whereIn('product_id', $products)->pluck('size_id');
            $weights = Weight::whereIn('id', $weight_ids)
            ->where('weight_value', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $weights;
        }
    }
}
