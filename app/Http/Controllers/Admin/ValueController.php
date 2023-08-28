<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\ValueResource;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValueController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $attributeValue = AttributeValue::with('attribute')->orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all attribute values",200, [
            "attributes" => ValueResource::collection($attributeValue),
            "links" => ValueResource::collection($attributeValue)->response()->getData()->links,
            "meta" => ValueResource::collection($attributeValue)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "attribute_id" => "required",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $value = AttributeValue::create([
            "name" => $request->name,
            "attribute_id" => $request->attribute_id,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "attribute value created successfully",
            201, 
            new ValueResource($value)
        );
    }

    public function show(AttributeValue $attributeValue)
    {
        return $this->succesResponse(
            "attribute value $attributeValue->name",
            200,
            new ValueResource($attributeValue->load('attribute'))
        );
    }

    public function update(Request $request, AttributeValue $attributeValue)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string"],
            "attribute_id" => "required",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $attributeValue->update([
            "name" => $request->name,
            "attribute_id" => $request->attribute_id,
            "status" => $request->status ?? $attributeValue->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "attribute value updated successfully",
            200, 
            new ValueResource($attributeValue)
        );
    }

    public function destroy(AttributeValue $attributeValue)
    {
        DB::beginTransaction();
        $attributeValue->delete();
        DB::commit();
        return $this->succesResponse("attribute value deleted successfully",200, new ValueResource($attributeValue));
    }

    public function changeValueStatus(Request $request, AttributeValue $attributeValue)
    {
        $attributeValue->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("attribute's value status updated successfully",200, new ValueResource($attributeValue));
    }
}
