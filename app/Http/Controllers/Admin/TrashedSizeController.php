<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SizeResource;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedSizeController extends ApiController
{
    public function trashedSizes(Request $request)
    {
        // $per_page = $request->count ?? 5;
        $sizes = DB::table('sizes')->whereNotNull('deleted_at')->paginate(10);
        return $this->succesResponse("admin trashed sizes",200, [
            "sizes" => SizeResource::collection($sizes),
            "links" => SizeResource::collection($sizes)->response()->getData()->links,
            "meta" => SizeResource::collection($sizes)->response()->getData()->meta,
        ]);
    }

    public function restoreSize($id)
    {
        Size::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("size is restored successfully",200, "");
    }

    public function forceDeleteSize($id)
    {
        Size::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("size force deleted successfully",200, "");
    }
}
