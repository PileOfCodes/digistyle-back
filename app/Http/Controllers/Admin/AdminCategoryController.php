<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminCategoryResource;
use App\Models\Category;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $categories = Category::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $categories = Category::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("admin categories",200, [
            "categories" => AdminCategoryResource::collection($categories),
            "links" => AdminCategoryResource::collection($categories)->response()->getData()->links,
            "meta" => AdminCategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required","min:3"],
            "name" => ["required"],
            "parent_category_id" => ["required","integer"],
            "image" => ["nullable","image"]
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/categories',$imageName,'public');
        }
        if($request->has('icon')) {
            
            $iconName = Carbon::now()->microsecond .'.'. $request->icon->extension();
            $request->icon->storeAs('images/categories',$iconName,'public');
        }
        DB::beginTransaction();
        $category = Category::create([
            "title" => $request->title,
            "name" => $request->name,
            "image" => $request->has('image') ? $imageName : null,
            "icon" => $request->has('icon') ? $iconName : null,
            "parent_category_id" => $request->parent_category_id ,
            "status" => $request->status ?? 1
        ]);
        DB::commit();
        return $this->succesResponse(
            "category created successfully",
            201, 
            new AdminCategoryResource($category)
        );
    }

    public function show(Category $category)
    {
        return $this->succesResponse("category $category->id",200,new AdminCategoryResource($category));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(),[
            // "title" => ["required","min:3"],
            // "name" => ["required"],
            // "parent_category_id" => ["required","integer"],
            // "image" => ["nullable","image"],
            "icon" => ["nullable","image"],
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image')) {
            
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/categories',$imageName,'public');
        }

        if($request->has('icon')) {
            
            $iconName = Carbon::now()->microsecond .'.'. $request->icon->extension();
            $request->icon->storeAs('images/categories',$iconName,'public');
        }
        DB::beginTransaction();
        $category->update([
            // "title" => $request->title,
            // "name" => $request->name,
            "image" => $request->has('image') ? $imageName : $category->image,
            "icon" => $request->has('icon') ? $iconName : $category->icon,
            "parent_category_id" => $request->parent_category_id ?? $category->parent_category_id,
            "status" => $request->status ?? $category->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "category updated successfully",
            200, 
            new AdminCategoryResource($category)
        );
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        $category->delete();
        DB::commit();
        return $this->succesResponse("category deleted successfully",200,new AdminCategoryResource($category));
    }

    public function changeCategoryStatus(Request $request,Category $category)
    {
        $category->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("category status updated successfully",200, new AdminCategoryResource($category));
    }
}
