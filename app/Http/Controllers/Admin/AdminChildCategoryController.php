<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminChildCategoryResource;
use App\Models\ChildCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminChildCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $childCategories = ChildCategory::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $childCategories = ChildCategory::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("admin childcategories",200, [
            "subcategories" => AdminChildCategoryResource::collection($childCategories),
            "links" => AdminChildCategoryResource::collection($childCategories)->response()->getData()->links,
            "meta" => AdminChildCategoryResource::collection($childCategories)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "subCategory_id" => ["required"],
            "name" => ["required"],
            "image" => 'nullable,image'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image'))
        {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->saveAs('images/categories', $imageName, 'public');
        }
        DB::beginTransaction();
        $childCategory = ChildCategory::create([
            "title" => $request->title,
            "name" => $request->name,
            "image" => $request->has('image') ? $imageName : null,
            "subCategory_id" => $request->subCategory_id,
            "status" => $request->has('status') ? $request->status : 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "childcategory created successfully",
            201, 
            new AdminChildCategoryResource($childCategory)
        );
    }

    public function show(ChildCategory $childCategory)
    {
        return $this->succesResponse(
            "childcategory $childCategory->id",
            200,
            new AdminChildCategoryResource($childCategory->load('parent')));
    }


    public function update(Request $request, ChildCategory $childCategory)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "subCategory_id" => ["required"],
            "name" => ["required"],
            "image" => "nullable,image"
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image'))
        {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->saveAs('images/categories', $imageName, 'public');
        }
        DB::beginTransaction();
        $childCategory->update([
            "title" => $request->title,
            "name" => $request->name,
            "image" => $request->has('image') ? $imageName : $request->image,
            "subCategory_id" => $request->subCategory_id,
            "status" => $request->has('status') ? $request->status : $childCategory->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "childcategory updated successfully",
            200, 
            new AdminChildCategoryResource($childCategory)
        );   
    }

    public function destroy(ChildCategory $childCategory)
    {
        DB::beginTransaction();
        $childCategory->delete();
        DB::commit();
        return $this->succesResponse("childcategory deleted successfully",200,new AdminChildCategoryResource($childCategory));
    }

    public function changeChildCategoryStatus(Request $request,ChildCategory $childCategory)
    {
        $childCategory->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("childcategory status updated successfully",200, new AdminChildCategoryResource($childCategory));
    }
}
