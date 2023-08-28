<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminDiscountController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $discounts = Discount::orderBy($orderBy)
        ->where('code', 'LIKE', "%{$request->search}%")
        ->orWhere('percent', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all discounts",200, [
            "discounts" => DiscountResource::collection($discounts),
            "links" => DiscountResource::collection($discounts)->response()->getData()->links,
            "meta" => DiscountResource::collection($discounts)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "percent" => "required|integer",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $discount = Discount::create([
            "code" => Str::random(10),
            "percent" => $request->percent,
            "expired_at" => $request->expired_at,
            "status" => $request->status ?? 1
            
        ]);
        DB::commit();
        return $this->succesResponse(
            "discount created successfully",
            201, 
            new DiscountResource($discount)
        );
    }

    public function show(Discount $discount)
    {
        return $this->succesResponse("discount $discount->name", 200, new DiscountResource($discount));
    }

    public function update(Request $request, Discount $discount)
    {
        $validator = Validator::make($request->all(), [
            "percent" => "required|integer",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $discount->update([
            "code" => $request->has('code') ? $request->code : $discount->code,
            "expired_at" => $request->expired_at ?? $discount->expired_at,
            "percent" => $request->percent ?? $discount->percent,
            "status" => $request->status ?? $discount->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "discount updated successfully",
            200, 
            new DiscountResource($discount)
        );
    }

    public function destroy(Discount $discount)
    {
        DB::beginTransaction();
        $discount->delete();
        DB::commit();
        return $this->succesResponse("discount deleted successfully",200, new DiscountResource($discount));
    }

    public function changeDiscountStatus(Request $request, Discount $discount)
    {
        $discount->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("discount status updated successfully",200, new DiscountResource($discount));
    }
}
