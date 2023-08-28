<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\WarrantResource;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WarrantController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $warranties = Warranty::orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all warranties",200, [
            "warranties" => WarrantResource::collection($warranties),
            "links" => WarrantResource::collection($warranties)->response()->getData()->links,
            "meta" => WarrantResource::collection($warranties)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|unique:warranties,name",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $warranty = Warranty::create([
            "name" => $request->name,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "warranty created successfully",
            201, 
            new WarrantResource($warranty)
        );
    }

    public function show(Warranty $warranty)
    {
        return $this->succesResponse("warranty $warranty->name", 200, new WarrantResource($warranty));
    }

    public function update(Request $request, Warranty $warranty)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string",Rule::unique('warranties')->ignore($warranty->id)],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $warranty->update([
            "name" => $request->name,
            "status" => $request->status ?? $warranty->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "warranty updated successfully",
            200, 
            new WarrantResource($warranty)
        );
    }

    public function destroy(Warranty $warranty)
    {
        DB::beginTransaction();
        $warranty->delete();
        DB::commit();
        return $this->succesResponse("warranty deleted successfully",200, new WarrantResource($warranty));
    }

    public function changeWarrantyStatus(Request $request, Warranty $warranty)
    {
        $warranty->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("warranty status updated successfully",200, new WarrantResource($warranty));
    }
}
