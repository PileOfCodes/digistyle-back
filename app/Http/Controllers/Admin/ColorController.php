<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ColorController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $colors = DB::table('colors')->orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->orWhere('color_value', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all colors",200, [
            "colors" => ColorResource::collection($colors),
            "links" => ColorResource::collection($colors)->response()->getData()->links,
            "meta" => ColorResource::collection($colors)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "color_value" => "required|string",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $color = Color::create([
            "name" => $request->name,
            "color_value" => $request->color_value,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "color created successfully",
            201, 
            new ColorResource($color)
        );
    }

    public function show(Color $color)
    {
        return $this->succesResponse("color $color->name", 200, new ColorResource($color));
    }

    public function update(Request $request, Color $color)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string"],
            "color_value" => "nullable|string",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $color->update([
            "name" => $request->name,
            "color_value" => $request->color_value ?? $color->color_value,
            "status" => $request->status ?? $color->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "color updated successfully",
            200, 
            new ColorResource($color)
        );
    }

    public function destroy(Color $color)
    {
        DB::beginTransaction();
        $color->delete();
        DB::commit();
        return $this->succesResponse("color deleted successfully",200, new ColorResource($color));
    }

    public function changeColorStatus(Request $request, Color $color)
    {
        $color->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("color status updated successfully",200, new ColorResource($color));
    }
}
