<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable, FilterQueryString;
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(ChildCategory::class,'childCategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'product_sellers')->withPivot('discount_id','warrant_id','sending_time','price');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class,'product_colors');
    }

    public function weights()
    {
        return $this->belongsToMany(Weight::class,'product_weights');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class,'product_sizes')->withPivot('quantity');
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeValue::class,'attribute_value_products');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class,'likes');
    }
    

    public static function filters()
    {
        $collection = Collection::where('slug', request()->slug)->first();
        if(isset($collection)) {
            $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
            $products = Product::whereIn('id', $proIds)->get();
        }
        $child = ChildCategory::where('slug', request()->slug)->first();
        if (isset($child)) {
            $products = Product::where('childCategory_id', $child->id)->get();
        }
        $subCategory = SubCategory::where('slug', request()->slug)->first();
        if (isset($subCategory)) {
            $childIds = ChildCategory::where('subCategory_id', $subCategory->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $childIds)->get();
        }
        $single = SingleCategory::where('slug', request()->slug)->first();
        if (isset($single)) {
            $childIds = ChildCategory::where('singleCategory_id', $single->id)->pluck('id');
            $products = Product::whereIn('childCategory_id', $childIds)->get();
        }
        $productsId = collect([]);
        $requestCount = 1;
        if(count(request()->all()) == 1) {
            return $products;
        }

        if(request()->has('search')) {
            $value = request()->search;
            $proSearchId = Product::where('title', 'LIKE', "%{$value}%")
            ->orWhere('name', 'LIKE', "%{$value}%")->pluck('id');
            return $products->find($proSearchId);
        }

        if(request()->has('mostVisited')) {
            if (isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->orderBy('visit','desc')->get();
                return $products;
            }elseif (isset($child)) {
                $products = Product::where('childCategory_id', $child->id)->orderBy('visit','desc')->get();
                return $products;
            }elseif (isset($subCategory)) {
                $products = Product::whereIn('childCategory_id', $childIds)->orderBy('visit','desc')->get();
                return $products;
            }
        }

        if(request()->has('mostSells')) {
            if (isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->orderBy('sell_count','desc')->get();
                return $products;
            }elseif (isset($child)) {
                $products = Product::where('childCategory_id', $child->id)->orderBy('sell_count','desc')->get();
                return $products;
            }elseif (isset($subCategory)) {
                $products = Product::whereIn('childCategory_id', $childIds)->orderBy('sell_count','desc')->get();
                return $products;
            }
        }

        if(request()->has('newest')) {
            if (isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->orderBy('created_at','desc')->get();
                return $products;
            }elseif (isset($child)) {
                $products = Product::where('childCategory_id', $child->id)->orderBy('created_at','desc')->get();
                return $products;
            }elseif (isset($subCategory)) {
                $products = Product::whereIn('childCategory_id', $childIds)->orderBy('created_at','desc')->get();
                return $products;
            }
        }

        if(request()->has('lowToHigh')) {
            if (isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $proSellers = ProductSeller::whereIn('product_id', $proIds)->orderBy('price','asc')->pluck('product_id');
                $products = Product::whereIn('id', $proSellers)->get();
                return $products;
            }elseif($child) {
                $proIds = ProductSeller::orderBy('price','asc')->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->get();
                return $products;
            }elseif (isset($subCategory)) {
                $proIds = ProductSeller::orderBy('price','asc')->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->whereIn('childCategory_id', $childIds)->get();
                return $products;
            }
        }

        if(request()->has('highToLow')) {
            if (isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $proSellers = ProductSeller::whereIn('product_id', $proIds)->orderBy('price','desc')->pluck('product_id');
                $products = Product::whereIn('id', $proSellers)->get();
                return $products;
            }elseif($child) {
                $proIds = ProductSeller::orderBy('price','desc')->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->get();
                return $products;
            }elseif (isset($subCategory)) {
                $proIds = ProductSeller::orderBy('price','asc')->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->whereIn('childCategory_id', $childIds)->get();
                return $products;
            }
        }

        if(request()->has('mostFavorite')) {
            if(isset($collection)) {
                $proIds = CollectionProduct::where('collection_id', $collection->id)->pluck('product_id');
                $products = Product::whereIn('id', $proIds)->withCount('likes')->get();
                return $products;
            }elseif($child) {
                $products = Product::where('childCategory_id', $child->id)->withCount('likes')->get();
                return $products;
            }elseif (isset($subCategory)) {
                $products = Product::whereIn('childCategory_id', $childIds)->withCount('likes')->get();
                return $products;
            }
        }


        $arr = array();
        $pairs = explode('&', request()->items);
        foreach ($pairs as $pair) {
            list($name, $value) = explode('=', $pair, 2);
            if( isset($arr[$name]) ) {
                if( is_array($arr[$name]) ) {
                    $arr[$name][] = $value;
                }
                else {
                  $arr[$name] = array($arr[$name], $value);
                }
              }
              else {
                $arr[$name] = $value;
            }
        }

        foreach ($arr as $key => $value) {
            if('sellers' == substr($key,0,7)) {
                $sellerPriority = array($value);
                $requestCount +=1;
                $sellerIds = Seller::whereIn('selected', $sellerPriority)->pluck('id');
                if (count($sellerIds)) {
                    $proSellerId = ProductSeller::whereIn('seller_id', $sellerIds)->pluck('product_id');
                    if (count($proSellerId)) {
                        $productsId->push(...$proSellerId);
                    }else {
                        return collect([]);
                    }
                }else{
                    return collect([]);
                }
            }

            if('brand' == substr($key,0,5)) {
                $brandIds = array($value);
                $requestCount +=1;
                $proBrandId = Product::whereIn('brand_id', $brandIds)->pluck('id');
                $productsId->push(...$proBrandId);
            }
            
            if('size' == substr($key,0,4)) {
                $sizeIds = array($value);
                $requestCount +=1;
                $proSizeId = ProductSize::whereIn('size_id', $sizeIds)->pluck('product_id');
                $productsId->push(...$proSizeId);
            }

            if('weights' == substr($key,0,7)) {
                $weightIds = array($value);
                $requestCount +=1;
                $proSizeId = ProductWeight::whereIn('weight_id', $weightIds)->pluck('product_id');
                $productsId->push(...$proSizeId);
            }

            if('color' == substr($key,0,5)) {
                $colorIds = array($value);
                $requestCount +=1;
                $proColorId = ProductColor::whereIn('color_id', $colorIds)->pluck('product_id');
                $productsId->push(...$proColorId);
            }

            if('types' == substr($key,0,5)) {
                $typeIds = array($value);
                $requestCount +=1;
                $proTypesId = AttributeValueProduct::whereIn('attribute_value_id', $typeIds)->pluck('product_id');
                $productsId->push(...$proTypesId);
            }

            if('designs' == substr($key,0,7)) {
                $designIds = array($value);
                $requestCount +=1;
                $proDesignId = AttributeValueProduct::whereIn('attribute_value_id', $designIds)->pluck('product_id');
                $productsId->push(...$proDesignId);
            }

            if('fasten' == substr($key,0,6)) {
                $fastenIds = array($value);
                $requestCount +=1;
                $proFastenId = AttributeValueProduct::whereIn('attribute_value_id', $fastenIds)->pluck('product_id');
                $productsId->push(...$proFastenId);
            }

            if('categories' == substr($key,0,10)) {
                $categoryIds = array($value);
                $requestCount +=1;
                $proCategoryId = Product::whereIn('childCategory_id', $categoryIds)->pluck('id');
                $productsId->push(...$proCategoryId);
            }

            if('materials' == substr($key,0,9)) {
                $fastenIds = array($value);
                $requestCount +=1;
                $proMaterialsId = AttributeValueProduct::whereIn('attribute_value_id', $fastenIds)->pluck('product_id');
                $productsId->push(...$proMaterialsId);
            }

            if('startCountry' == substr($key,0,12)) {
                $startIds = array($value);
                $requestCount +=1;
                $proCountryId = AttributeValueProduct::whereIn('attribute_value_id', $startIds)->pluck('product_id');
                $productsId->push(...$proCountryId);
            }

            if('clothTypes' == substr($key,0,10)) {
                $clothIds = array($value);
                $requestCount +=1;
                $proClothId = AttributeValueProduct::whereIn('attribute_value_id', $clothIds)->pluck('product_id');
                $productsId->push(...$proClothId);
            }

            if('height' == substr($key,0,6)) {
                $heightIds = array($value);
                $requestCount +=1;
                $proHeightId = AttributeValueProduct::whereIn('attribute_value_id', $heightIds)->pluck('product_id');
                $productsId->push(...$proHeightId);
            }

            if('special' == substr($key,0,7)) {
                $specialIds = array($value);
                $requestCount +=1;
                $proSpecialId = AttributeValueProduct::whereIn('attribute_value_id', $specialIds)->pluck('product_id');
                $productsId->push(...$proSpecialId);
            }
        }

        $selected = collect([]); 
        switch ($requestCount) {
            case 2:
                $selected->push($productsId);
                break;
            case 3:
                $selected->push($productsId->duplicates());
                break;
            case 4:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 5:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 6:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 7:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 8:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 9:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates()
                ->duplicates());
                $selected->push(...$stack1);
                break;
            case 10:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates()
                ->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 11:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
            case 12:
                $stack1 = collect([]);
                $stack1->push($productsId->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates()
                ->duplicates()->duplicates()->duplicates()->duplicates());
                $selected->push(...$stack1);
                break;
        }
        if (isset($collection)) {
           return $products->find(...$selected);
        }elseif (isset($child)) {
            return $products->find(...$selected);
        }elseif (isset($subCategory)) {
            return $products->find(...$selected);
        }elseif (isset($single)) {
            return $products->find(...$selected);
        }
    }

}
