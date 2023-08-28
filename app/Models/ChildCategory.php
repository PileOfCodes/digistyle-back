<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ChildCategory extends Model
{
    use HasFactory, SoftDeletes, Sluggable;
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function parent() {
        return $this->belongsTo(SubCategory::class,'subCategory_id');
    }

    public function filters() {
        return $this->belongsToMany(ChildCategoryFilter::class, 'child_category_filters');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'childCategory_id');
    }
}
