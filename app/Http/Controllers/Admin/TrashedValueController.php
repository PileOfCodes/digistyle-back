<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\TrashedValueResource;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedValueController extends ApiController
{
    public function trashedValues(Request $request)
    {
        $per_page = $request->count ?? 5;
        $brands = DB::table('attribute_values')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed attribute values",200, [
            "attributes" => TrashedValueResource::collection($brands),
            "links" => TrashedValueResource::collection($brands)->response()->getData()->links,
            "meta" => TrashedValueResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function restoreValue($id)
    {
        AttributeValue::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("attribute value is restored successfully",200, "");
    }

    public function forceDeleteValue($id)
    {
        AttributeValue::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("attribute value force deleted successfully",200, "");
    }
}
