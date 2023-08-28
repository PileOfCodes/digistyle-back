<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminAttributeController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $attributes = Attribute::with('category')->orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all attributes",200, [
            "attributes" => AttributeResource::collection($attributes),
            "links" => AttributeResource::collection($attributes)->response()->getData()->links,
            "meta" => AttributeResource::collection($attributes)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|unique:brands,title",
            "childCategory_id" => "required",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $attribute = Attribute::create([
            "name" => $request->name,
            "childCategory_id" => $request->childCategory_id,
            "priority" => $request->priority ?? 2,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "attribute created successfully",
            201, 
            new AttributeResource($attribute)
        );
    }

    public function show(Attribute $attribute)
    {
        return $this->succesResponse(
            "attribute $attribute->name",
            200,
            new AttributeResource($attribute->load('category')->load('parent')->load('children'))
        );
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string",Rule::unique('attributes')->ignore($attribute->id)],
            "childCategory_id" => "nullable|integer",
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $attribute->update([
            "name" => $request->name,
            "childCategory_id" => $request->childCategory_id ?? $attribute->childCategory_id,
            "priority" => $request->priority ?? $attribute->priority,
            "status" => $request->status ?? $attribute->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "attribute updated successfully",
            200, 
            new AttributeResource($attribute)
        );
    }

    public function destroy(Attribute $attribute)
    {
        DB::beginTransaction();
        $attribute->delete();
        DB::commit();
        return $this->succesResponse("attribute deleted successfully",200, new AttributeResource($attribute));
    }

    public function changeAttributeStatus(Request $request, Attribute $attribute)
    {
        $attribute->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("attribute status updated successfully",200, new AttributeResource($attribute));
    }
}
