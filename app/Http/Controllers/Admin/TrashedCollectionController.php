<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\CollectionResource;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;

class TrashedCollectionController extends ApiController
{
    public function trashedColors()
    {
        $collections = DB::table('collections')->whereNotNull('deleted_at')->paginate(10);
        return $this->succesResponse("admin trashed collections",200, [
            "collections" => CollectionResource::collection($collections),
            "links" => CollectionResource::collection($collections)->response()->getData()->links,
            "meta" => CollectionResource::collection($collections)->response()->getData()->meta,
        ]);
    }

    public function restoreColor($id)
    {
        Collection::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("collection is restored successfully",200, "");
    }

    public function forceDeleteColor($id)
    {
        Collection::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("collection force deleted successfully",200, "");
    }
}
