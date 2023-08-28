<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedColorController extends ApiController
{
    public function trashedColors(Request $request)
    {
        $per_page = $request->count ?? 5;
        $colors = DB::table('colors')->whereNotNull('deleted_at')->paginate($per_page);
        return $this->succesResponse("admin trashed colors",200, [
            "colors" => ColorResource::collection($colors),
            "links" => ColorResource::collection($colors)->response()->getData()->links,
            "meta" => ColorResource::collection($colors)->response()->getData()->meta,
        ]);
    }

    public function restoreColor($id)
    {
        Color::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("color is restored successfully",200, "");
    }

    public function forceDeleteColor($id)
    {
        Color::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("color force deleted successfully",200, "");
    }
}
