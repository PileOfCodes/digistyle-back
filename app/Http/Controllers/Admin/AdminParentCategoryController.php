<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\ParentCategoryResource;
use App\Models\ParentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminParentCategoryController extends ApiController
{
    public function index()
    {
        $parentCategories = ParentCategory::paginate(10);
        return $this->succesResponse("all parent categories", 200, [
            "parentCategories" => ParentCategoryResource::collection($parentCategories),
            "links" => ParentCategoryResource::collection($parentCategories)->response()->getData()->links,
            "meta" => ParentCategoryResource::collection($parentCategories)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string|unique:parent_categories,title",
            "name" => "required|string"
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 421);
        }
        DB::beginTransaction();
        $parent = ParentCategory::create([
            "title" => $request->title,
            "name" => $request->name,
            "status" => $request->status ? $request->status : 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "parent category created successfully",
            201,
            new ParentCategoryResource($parent)
        );
    }

    public function show(ParentCategory $parentCategory)
    {
        return $this->succesResponse(
            "parent category {$parentCategory->title}",
            200,
            new ParentCategoryResource($parentCategory)
        );
    }

    public function update(Request $request, ParentCategory $parentCategory)
    {
        $validator = Validator::make($request->all(), [
            "title" => ["required","string", Rule::unique('parent_categories')->ignore($parentCategory->id)],
            "name" => "required|string"
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 421);
        }
        DB::beginTransaction();
        $parentCategory->update([
            "title" => $request->title,
            "name" => $request->name,
            "status" => $request->status ? $request->status : $parentCategory->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "parent category updated successfully",
            200,
            new ParentCategoryResource($parentCategory)
        );
    }

    public function destroy(ParentCategory $parentCategory)
    {
        DB::beginTransaction();
        $parentCategory->delete();
        DB::commit();
        return $this->succesResponse(
            "parent category deleted successfully",
            200,
            ""
        );
    }

    public function changeParentStatus(Request $request,ParentCategory $parentCategory)
    {
        $parentCategory->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("parent category status updated successfully",200, new ParentCategoryResource($parentCategory));
    }
}
