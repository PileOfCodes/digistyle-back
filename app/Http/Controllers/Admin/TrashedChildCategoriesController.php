<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\TrashedChildCategoryResource;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedChildCategoriesController extends ApiController
{
    public function trashedChildCategories(Request $request)
    {
        $per_page = $request->count ?? 5;
        $childCategories = DB::table('child_categories')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed childcategories",200, [
            "childCategories" => TrashedChildCategoryResource::collection($childCategories),
            "links" => TrashedChildCategoryResource::collection($childCategories)->response()->getData()->links,
            "meta" => TrashedChildCategoryResource::collection($childCategories)->response()->getData()->meta,
        ]);
    }

    public function restoreChildCategory($id)
    {
        ChildCategory::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("childcategory is restored successfully",200, "");
    }

    public function forceDeleteChildCategory($id)
    {
        ChildCategory::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("childcategory force deleted successfully",200, "");
    }
}
