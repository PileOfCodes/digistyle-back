<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes, Sluggable;
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'code'
            ]
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function slider()
    {
        return $this->hasOne(Slider::class);
    }

    public function categories()
    {
        return $this->belongsToMany(ChildCategory::class, 'child_category_collections');
    }
}
