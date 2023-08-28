<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SingleCategory extends Model
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

    public function porducts()
    {
        return $this->hasMany(Product::class);
    }

    public function children()
    {
        return $this->hasMany(ChildCategory::class, 'singleCategory_id');
    }
}
