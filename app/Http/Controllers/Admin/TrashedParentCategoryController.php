<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\ParentCategoryResource;
use App\Models\ParentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedParentCategoryController extends ApiController
{
    public function trashedParent(Request $request)
    {
        $per_page = $request->count ?? 5;
        $categories = DB::table('parent_categories')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed parent categories",200, [
            "parent categories" => ParentCategoryResource::collection($categories),
            "links" => ParentCategoryResource::collection($categories)->response()->getData()->links,
            "meta" => ParentCategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    public function restoreParent($id)
    {
        ParentCategory::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("parent category is restored successfully",200, "");
    }

    public function forceDeleteParent($id)
    {
        ParentCategory::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("parent category force deleted successfully",200, "");
    }
}
