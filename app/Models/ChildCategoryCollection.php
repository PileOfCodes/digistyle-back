<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategoryCollection extends Model
{
    use HasFactory;
    protected $table = 'child_category_collections';
    protected $guarded = [];
}
