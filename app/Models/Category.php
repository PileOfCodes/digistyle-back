<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
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

    public function parent()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_category_id');
    }

    public function subCategories() {
        return $this->hasMany(SubCategory::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_categories');
    }
}
