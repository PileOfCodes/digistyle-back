<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AdminDemandResource;
use App\Models\Demand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminDemandController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $demands = Demand::orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all demands",200, [
            "demands" => AdminDemandResource::collection($demands),
            "links" => AdminDemandResource::collection($demands)->response()->getData()->links,
            "meta" => AdminDemandResource::collection($demands)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "demand" => "required|string",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $demand = Demand::create([
            "demand" => $request->demand,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "demand created successfully",
            201, 
            new AdminDemandResource($demand)
        );
    }

    public function show(Demand $demand)
    {
        return $this->succesResponse("demand $demand->name", 200, new AdminDemandResource($demand));
    }

    public function update(Request $request, Demand $demand)
    {
        $validator = Validator::make($request->all(), [
            "demand" => ["required","string",Rule::unique('demands')->ignore($demand->id)],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $demand->update([
            "demand" => $request->demand,
            "status" => $request->status ?? $demand->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "demand updated successfully",
            200, 
            new AdminDemandResource($demand)
        );
    }

    public function destroy(Demand $demand)
    {
        DB::beginTransaction();
        $demand->delete();
        DB::commit();
        return $this->succesResponse("demand deleted successfully",200, new AdminDemandResource($demand));
    }

    public function changeDemandStatus(Request $request, Demand $demand)
    {
        $demand->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("demand status updated successfully",200, new AdminDemandResource($demand));
    }
}
