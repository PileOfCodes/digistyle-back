<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SizeResource;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SizeController extends ApiController
{
    public function index(Request $request)
    {
        $orderBy = $request->sortBy ?? 'id';
        $sizes = DB::table('sizes')->orderBy($orderBy)
        ->where('size_value', 'LIKE', "%{$request->search}%")
        ->paginate(10);
        return $this->succesResponse("all sizes",200, [
            "sizes" => SizeResource::collection($sizes),
            "links" => SizeResource::collection($sizes)->response()->getData()->links,
            "meta" => SizeResource::collection($sizes)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "size_value" => ["required","string","unique:sizes,size_value"],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $size = Size::create([
            "size_value" => $request->color_value,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "size created successfully",
            201, 
            new SizeResource($size)
        );
    }

    public function show(Size $size)
    {
        return $this->succesResponse("Size $size->size_value", 200, new SizeResource($size));
    }

    public function update(Request $request, Size $size)
    {
        $validator = Validator::make($request->all(), [
            "size_value" => ["required","string", Rule::unique('sizes')->ignore($size->id)],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $size->update([
            "size_value" => $request->color_value,
            "status" => $request->status ?? $size->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "size updated successfully",
            200, 
            new SizeResource($size)
        );
    }

    public function destroy(Size $size)
    {
        DB::beginTransaction();
        $size->delete();
        DB::commit();
        return $this->succesResponse("size deleted successfully",200, new SizeResource($size));
    }

    public function changeSizeStatus(Request $request, Size $size)
    {
        $size->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("size status updated successfully",200, new SizeResource($size));
    }
}
