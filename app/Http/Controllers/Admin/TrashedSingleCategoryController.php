<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Front\SingleCategoryResource;
use App\Models\SingleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedSingleCategoryController extends ApiController
{
    public function trashedSingleCategories(Request $request)
    {
        $per_page = $request->count ?? 5;
        $categories = DB::table('single_categories')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed single categories",200, [
            "categories" => SingleCategoryResource::collection($categories),
            "links" => SingleCategoryResource::collection($categories)->response()->getData()->links,
            "meta" => SingleCategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    public function restoreSingleCategory($id)
    {
        $category = SingleCategory::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("single category is restored successfully",200, "");
    }

    public function forceDeleteSingleCategory($id)
    {
        $category = SingleCategory::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("single category force deleted successfully",200, "");
    }
}
