<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AttributeResource;
use App\Http\Resources\Admin\TrashedAttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedAttributeController extends ApiController
{
    public function trashedAttributes(Request $request)
    {
        $per_page = $request->count ?? 5;
        $brands = DB::table('attributes')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed attributes",200, [
            "attributes" => TrashedAttributeResource::collection($brands),
            "links" => TrashedAttributeResource::collection($brands)->response()->getData()->links,
            "meta" => TrashedAttributeResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function restoreAttribute($id)
    {
        Attribute::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("attribute is restored successfully",200, "");
    }

    public function forceDeleteAttribute($id)
    {
        Attribute::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("attribute force deleted successfully",200, "");
    }
}
