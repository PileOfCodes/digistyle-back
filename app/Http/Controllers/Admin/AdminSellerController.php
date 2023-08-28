<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\SellerResource;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AdminSellerController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $sellers = Seller::orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->where('code', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all sellers",200, [
            "sellers" => SellerResource::collection($sellers),
            "links" => SellerResource::collection($sellers)->response()->getData()->links,
            "meta" => SellerResource::collection($sellers)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $seller = Seller::create([
            "name" => $request->name,
            "code" => Str::random(5),
            "membership_time" => $request->membership_time,
            "selected" => $request->selected ?? 0,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "seller created successfully",
            201, 
            new SellerResource($seller)
        );
    }

    public function show(Seller $seller)
    {
        return $this->succesResponse(
            "seller $seller->name",
            200,
            new SellerResource($seller)
        );
    }

    public function update(Request $request, Seller $seller)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string"],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $seller->update([
            "name" => $request->name,
            "selected" => $request->selected ?? $seller->selected,
            "membership_time" => $request->membership_time ?? $seller->membership_time,
            "status" => $request->status ?? $seller->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "seller updated successfully",
            200, 
            new SellerResource($seller)
        );
    }

    public function destroy(Seller $seller)
    {
        DB::beginTransaction();
        $seller->delete();
        DB::commit();
        return $this->succesResponse("seller deleted successfully",200, new SellerResource($seller));
    }

    public function changeSellerStatus(Request $request, Seller $seller)
    {
        $seller->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("seller status updated successfully",200, new SellerResource($seller));
    }
}
