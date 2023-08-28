<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\BrandCategoryResource;
use App\Models\BrandCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $brands = BrandCategory::paginate(5);
        
        return $this->succesResponse("all admin category brands", 200, [
            "category brands" => BrandCategoryResource::collection($brands),
            "links" => BrandCategoryResource::collection($brands)->response()->getData()->links,
            "meta" => BrandCategoryResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'category_id' => 'required',
            'brand_id' => 'required',
            'image' => 'required|image',
            'status' => 'nullabel|integer'
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }

        $categoryImageName = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('images/brands', $categoryImageName, 'public');

        DB::beginTransaction();
        $brand = BrandCategory::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'image' => $categoryImageName,
            'status' => $request->status ?? 1,
        ]);
        DB::commit();
        return $this->succesResponse("category brand created successfully",201, new BrandCategoryResource($brand));
    }

    public function show(BrandCategory $brandCategory)
    {
        return $this->succesResponse("category brand $brandCategory->name", 200, new BrandCategoryResource($brandCategory));
    }

    public function update(Request $request, BrandCategory $brandCategory)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'category_id' => 'nullable',
            'brand_id' => 'nullable',
            'image' => 'nullable|image',
            'status' => 'nullabel|integer'
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image'))
        {
            $categoryImageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/brands', $categoryImageName, 'public');
        }

        DB::beginTransaction();
        $brandCategory->update([
            'title' => $request->title,
            'category_id' => $request->category_id ?? $brandCategory->category_id,
            'brand_id' => $request->brand_id ?? $brandCategory->brand_id,
            'image' => $request->has('image') ? $categoryImageName : $brandCategory->image,
            'status' => $request->status ?? 1,
        ]);
        DB::commit();
        return $this->succesResponse("category brand updated successfully",200, new BrandCategoryResource($brandCategory));
    }

    public function destroy(BrandCategory $brandCategory)
    {
        DB::beginTransaction();
        $brandCategory->delete();
        DB::commit();
        return $this->succesResponse("category brand deleted successfully",200, new BrandCategoryResource($brandCategory));
    }

    public function changeCategoryBrandStatus(Request $request,BrandCategory $brandCategory)
    {
        $brandCategory->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("category brand status updated successfully",200, new BrandCategoryResource($brandCategory));
    }
}
