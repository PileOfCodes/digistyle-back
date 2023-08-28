<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminSubCategoryResource;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminSubCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $subCategories = SubCategory::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $subCategories = SubCategory::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("admin subcategories",200, [
            "subcategories" => AdminSubCategoryResource::collection($subCategories),
            "links" => AdminSubCategoryResource::collection($subCategories)->response()->getData()->links,
            "meta" => AdminSubCategoryResource::collection($subCategories)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "category_id" => ["required"],
            "name" => ["required"],
            "image" => ["nullable","image"]
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/subCategories',$imageName,'public');
        }
        $category = SubCategory::create([
            "title" => $request->title,
            "name" => $request->name,
            "category_id" => $request->category_id,
            "image" => $request->has('image') ? $imageName : null,
            "status" => $request->has('status') ? 0 : 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "subcategory created successfully",
            201, 
            new AdminSubCategoryResource($category)
        );
    }

    public function show(SubCategory $subCategory)
    {
        return $this->succesResponse(
            "subcategory $subCategory->id",
            200,
            new AdminSubCategoryResource($subCategory)
        );
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "category_id" => ["required"],
            "name" => ["required"],
            "image" => ["nullable","image"]
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/subCategories',$imageName,'public');
        }
        $subCategory->update([
            "title" => $request->title,
            "name" => $request->name,
            "category_id" => $request->category_id,
            "image" => $request->has('image') ? $imageName : $subCategory->image,
            "status" => $request->has('status') ? $request->status : $subCategory->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "subcategory updated successfully",
            200, 
            new AdminSubCategoryResource($subCategory)
        );   
    }

    public function destroy(SubCategory $subCategory)
    {
        DB::beginTransaction();
        $subCategory->delete();
        DB::commit();
        return $this->succesResponse("subcategory deleted successfully",200,new AdminSubCategoryResource($subCategory));
    }

    public function changeSubCategoryStatus(Request $request,SubCategory $subCategory)
    {
        $subCategory->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("subcategory status updated successfully",200, new AdminSubCategoryResource($subCategory));
    }
}
