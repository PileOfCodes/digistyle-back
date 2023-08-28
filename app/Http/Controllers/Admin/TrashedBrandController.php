<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TrashedBrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedBrandController extends ApiController
{
    public function trashedBrands(Request $request)
    {
        $per_page = $request->count ?? 5;
        $brands = DB::table('brands')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed brands",200, [
            "brands" => TrashedBrandResource::collection($brands),
            "links" => TrashedBrandResource::collection($brands)->response()->getData()->links,
            "meta" => TrashedBrandResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function restoreBrand($id)
    {
        Brand::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("brand is restored successfully",200, "");
    }

    public function forceDeleteBrand($id)
    {
        Brand::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("brand force deleted successfully",200, "");
    }
}
