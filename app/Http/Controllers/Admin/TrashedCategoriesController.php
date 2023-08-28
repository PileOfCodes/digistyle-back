<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminCategoryResource;
use App\Models\Category;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedCategoriesController extends ApiController
{
    public function trashedCategories(Request $request)
    {
        $per_page = $request->count ?? 5;
        $categories = DB::table('categories')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed categories",200, [
            "categories" => AdminCategoryResource::collection($categories),
            "links" => AdminCategoryResource::collection($categories)->response()->getData()->links,
            "meta" => AdminCategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    public function restoreCategory($id)
    {
        $category = Category::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("category is restored successfully",200, "");
    }

    public function forceDeleteCategory($id)
    {
        $category = Category::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("category force deleted successfully",200, "");
    }
}
