<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ChildCategory::class,'childCategory_id');
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function parent()
    {
        return $this->belongsTo(Attribute::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Attribute::class,'parent_id');
    }
}
