<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\BrandCategoryResource;
use App\Models\BrandCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedBrandCategoryController extends ApiController
{
    public function trashedBrands(Request $request)
    {
        $brands = DB::table('brand_categories')->whereNotNull('deleted_at')->paginate(5);
        return $this->succesResponse("admin trashed category brands",200, [
            "category brands" => BrandCategoryResource::collection($brands),
            "links" => BrandCategoryResource::collection($brands)->response()->getData()->links,
            "meta" => BrandCategoryResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function restoreBrand($id)
    {
        BrandCategory::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("category brand is restored successfully",200, "");
    }

    public function forceDeleteBrand($id)
    {
        BrandCategory::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("category brand force deleted successfully",200, "");
    }
}
