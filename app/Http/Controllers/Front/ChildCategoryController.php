<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Http\Resources\Admin\AdminProductResource;
use App\Http\Resources\Admin\ColorResource;
use App\Http\Resources\Admin\DiscountResource;
use App\Http\Resources\Admin\SizeResource;
use App\Http\Resources\Admin\ValueResource;
use App\Http\Resources\Front\ChildCategoryResource;
use App\Http\Resources\Front\FilterResource;
use App\Http\Resources\Front\ProductResource;
use App\Http\Resources\Front\SingleProductResource;
use App\Models\Attribute as ModelsAttribute;
use App\Models\AttributeValue;
use App\Models\AttributeValueProduct;
use App\Models\Brand;
use App\Models\ChildCategory;
use App\Models\ChildCategoryFilter;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Filter;
use App\Models\Like;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSeller;
use App\Models\ProductSize;
use App\Models\Seller;
use App\Models\Size;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ChildCategoryController extends ApiController
{
    public function getProducts(Request $reqest)
    {
        $products = Product::filters()->paginate(20);
        return $this->succesResponse('all categorized products', 200, [
            'products' => SingleProductResource::collection($products),
            "links" => SingleProductResource::collection($products)->response()->getData()->links,
            "meta" => SingleProductResource::collection($products)->response()->getData()->meta
        ]);
    }

    public function getChildCategory(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        return $this->succesResponse("child category", 200, new ChildCategoryResource($child));
    }

    public function getFilters(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        $filters = ChildCategoryFilter::where('childCategory_id', $child->id)->pluck('filter_id');
        $names = DB::table('filters')->whereIn('id', $filters)->pluck('name');
        return $this->succesResponse("child category filters", 200, new FilterResource($names));
    }

    public function getTypes(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "type"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "type"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function getMaterial(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                "english" => "material"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                "english" => "material"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function getHeight(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                "english" => "height"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                "english" => "height"
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function getClothType(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        $attribute = ModelsAttribute::where([
            "childCategory_id" => $child->id,
            "priority" => 1,
            "english" => "clothType"
        ])->first();
        $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
        return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
    }

    public function getSpecial(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        $attribute = ModelsAttribute::where([
            "childCategory_id" => $child->id,
            "priority" => 1,
            "english" => "special"
        ])->first();
        $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
        return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
    }

    public function getStartCountry(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        $attribute = ModelsAttribute::where([
            "childCategory_id" => $child->id,
            "english" => "startCountry"
        ])->first();
        $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
        return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
    }

    public function getFastenType(Request $request)
    {
        $child = ChildCategory::where('slug', $request->slug)->first();
        $attribute = ModelsAttribute::where([
            "childCategory_id" => $child->id,
            ["priority", '!=', 2],
            "english" => "fasten" 
        ])->first();
        $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
        return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
    }

    public function getSeasons(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "season" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "season" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function specialAttributes(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "special" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "special" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function getDesigns(Request $request)
    {
        if ($request->search == null || $request->search == '') 
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "design" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $attribute = ModelsAttribute::where([
                "childCategory_id" => $child->id,
                ["priority", '!=', 2],
                "english" => "design" 
            ])->first();
            $attribute_values = AttributeValue::where('attribute_id', $attribute->id)
            ->where('name', 'like', "%$request->search%")->get();
            return $this->succesResponse("all attribute values", 200, ValueResource::collection($attribute_values));
        }
    }

    public function allBrands(Request $request)
    {
        if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::query()->where('slug', $request->slug)->first();
            $items = Product::where('childCategory_id', $child->id)->pluck('brand_id');
            $brands = Brand::whereIn('id', $items)
            ->where('name', 'LIKE', "%{$request->search}%")
            // ->orWhere('title', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }
        if($request->search == null || $request->search == '')
        {
            $child = ChildCategory::query()->where('slug', $request->slug)->first();
            $items = Product::where('childCategory_id', $child->id)->pluck('brand_id');
            $brands = Brand::whereIn('id', $items)->get();
            return $this->succesResponse("all category brands", 200, AdminBrandResource::collection($brands));
        }

    }

    public function allSizes(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $child = ChildCategory::query()->where('slug', $request->slug)->first();
            $products = Product::where('childCategory_id', $child->id)->pluck('id');
            $size_ids = DB::table('product_sizes')->whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)->distinct()->get();
            return $this->succesResponse('sizes', 200, SizeResource::collection($sizes));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $products = Product::where('childCategory_id', $child->id)->pluck('id');
            $size_ids = DB::table('product_sizes')->whereIn('product_id', $products)->pluck('size_id');
            $sizes = Size::whereIn('id', $size_ids)
            ->where('size_value', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse('sizes', 200, SizeResource::collection($sizes));
        }
    }

    public function allColors(Request $request)
    {
        if($request->search == null || $request->search == '')
        {
            $child = ChildCategory::query()->where('slug', $request->slug)->first();
            $products = Product::where('childCategory_id', $child->id)->pluck('id');
            $color_ids = DB::table('product_colors')->whereIn('product_id', $products)->pluck('color_id');
            $colros = DB::table('colors')->whereIn('id', $color_ids)->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colros));
        }else if($request->search != null || $request->search != '')
        {
            $child = ChildCategory::where('slug', $request->slug)->first();
            $products = Product::where('childCategory_id', $child->id)->pluck('id');
            $color_ids = ProductColor::whereIn('product_id', $products)->pluck('color_id');
            $colors = Color::whereIn('id', $color_ids)
            ->where('color_value', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->distinct()->get();
            return $this->succesResponse("all category colors", 200, ColorResource::collection($colors));
        }
    }
    
    public function allPrices(Request $request)
    {
        $child = ChildCategory::query()->where('slug', $request->slug)->first();
        $products = Product::where('childCategory_id', $child->id)->pluck('id');
        $prices = DB::table('product_sellers')->whereIn('product_id', $products)->pluck('price');
        return $this->succesResponse("all category colors", 200, $prices);
    }
}
