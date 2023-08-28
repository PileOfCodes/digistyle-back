<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\WarrantResource;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedWarrantyController extends ApiController
{
    public function trashedWarranties(Request $request)
    {
        $per_page = $request->count ?? 5;
        $warranties = DB::table('warranties')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed warranties",200, [
            "warranties" => WarrantResource::collection($warranties),
            "links" => WarrantResource::collection($warranties)->response()->getData()->links,
            "meta" => WarrantResource::collection($warranties)->response()->getData()->meta,
        ]);
    }

    public function restoreWarranty($id)
    {
        Warranty::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("warranty is restored successfully",200, "");
    }

    public function forceDeleteWarranty($id)
    {
        Warranty::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("warranty force deleted successfully",200, "");
    }
}
