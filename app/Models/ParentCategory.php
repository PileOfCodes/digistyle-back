<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ParentCategory extends Model
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
        return $this->belongsTo(ParentCategory::class,'parent_id');
    }

    public function categories() 
    {
        return $this->hasMany(Category::class);
    }

    public function singleCategories() 
    {
        return $this->hasMany(SingleCategory::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
