<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminTrashedCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedSubCategoriesController extends ApiController
{
    public function trashedSubCategories(Request $request)
    {
        $per_page = $request->count ?? 5;
        $subCategories = DB::table('sub_categories')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed subcategories",200, [
            "subCategories" => AdminTrashedCategoryResource::collection($subCategories),
            "links" => AdminTrashedCategoryResource::collection($subCategories)->response()->getData()->links,
            "meta" => AdminTrashedCategoryResource::collection($subCategories)->response()->getData()->meta,
        ]);
    }

    public function restoreSubCategory($id)
    {
        SubCategory::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("subcategory is restored successfully",200, "");
    }

    public function forceDeleteSubCategory($id)
    {
        SubCategory::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("subcategory force deleted successfully",200, "");
    }
}
