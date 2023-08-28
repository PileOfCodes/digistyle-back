<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Front\SingleCategoryResource;
use App\Models\SingleCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminSingleCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $categories = SingleCategory::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $categories = SingleCategory::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("admin single categories",200, [
            "categories" => SingleCategoryResource::collection($categories),
            "links" => SingleCategoryResource::collection($categories)->response()->getData()->links,
            "meta" => SingleCategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "name" => ["required"],
            "parent_category_id" => ["required","integer"],
            "image" => ["nullable","image"],
            "primary_image" => ["nullable","image"]
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/categories',$imageName,'public');
        }
        if($request->has('primary_image')) {
            $primary_imageName = Carbon::now()->microsecond .'.'. $request->primary_image->extension();
            $request->primary_image->storeAs('images/categories',$primary_imageName,'public');
        }
        DB::beginTransaction();
        $category = SingleCategory::create([
            "title" => $request->title,
            "name" => $request->name,
            "image" => $request->has('image') ? $imageName : null,
            "primary_image" => $request->has('primary_image') ? $primary_imageName : null,
            "parent_category_id" => $request->parent_category_id ,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "category created successfully",
            201, 
            new SingleCategoryResource($category)
        );
    }

    public function show(SingleCategory $singleCategory)
    {
        return $this->succesResponse("SingleCategory $singleCategory->id",200,new SingleCategoryResource($singleCategory));
    }

    public function update(Request $request, SingleCategory $singleCategory)
    {
        $validator = Validator::make($request->all(),[
            // "title" => ["required","min:3"],
            // "name" => ["required"],
            // "parent_category_id" => ["required","integer"],
            // "image" => ["nullable","image"]
            "primary_image" => ["nullable","image"]
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image')) {
            
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/categories',$imageName,'public');
        }
        if($request->has('primary_image')) {
            $primary_imageName = Carbon::now()->microsecond .'.'. $request->primary_image->extension();
            $request->primary_image->storeAs('images/categories',$primary_imageName,'public');
        }
        DB::beginTransaction();
        $singleCategory->update([
            "title" => $request->title ?? $singleCategory->title,
            "name" => $request->name ?? $singleCategory->name,
            "image" => $request->has('image') ? $imageName : $singleCategory->image,
            "primary_image" => $request->has('primary_image') ? $primary_imageName : $singleCategory->primary_image,
            "parent_category_id" => $request->parent_category_id ?? $singleCategory->parent_category_id,
            "status" => $request->status ?? $singleCategory->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "single category updated successfully",
            200, 
            new SingleCategoryResource($singleCategory)
        );
    }

    public function destroy(SingleCategory $singleCategory)
    {
        DB::beginTransaction();
        $singleCategory->delete();
        DB::commit();
        return $this->succesResponse("category deleted successfully",200,new SingleCategoryResource($singleCategory));
    }

    public function changeSingleCategoryStatus(Request $request,SingleCategory $singleCategory)
    {
        $singleCategory->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("single category status updated successfully",200, new SingleCategoryResource($singleCategory));
    }
}
