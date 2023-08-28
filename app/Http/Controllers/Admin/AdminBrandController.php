<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminBrandController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $brands = Brand::orderBy($sort_by)
            ->where('title', 'LIKE', "%{$request->search}%")
            ->orWhere('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $brands = Brand::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("all admin brands", 200, [
            "brands" => AdminBrandResource::collection($brands),
            "links" => AdminBrandResource::collection($brands)->response()->getData()->links,
            "meta" => AdminBrandResource::collection($brands)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'name' => 'required',
            'description' => 'nullable',
            'iranian_designer' => 'nullable',
            'primary_image' => 'nullable|image',
            'image' => 'nullable|image'
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('primary_image'))
        {
            $primaryImageName = Carbon::now()->microsecond . '.' . $request->primary_image->extension();
            $request->primary_image->storeAs('images/brands', $primaryImageName, 'public');
        }
        if($request->has('brand_image'))
        {
            $brandImageName = Carbon::now()->microsecond . '.' . $request->brand_image->extension();
            $request->brand_image->storeAs('images/brands', $brandImageName, 'public');
        }
        if($request->has('slider_image'))
        {
            $sliderImageName = Carbon::now()->microsecond . '.' . $request->slider_image->extension();
            $request->slider_image->storeAs('images/brands', $sliderImageName, 'public');
        }
        if($request->has('category_image'))
        {
            $categoryImageName = Carbon::now()->microsecond . '.' . $request->category_image->extension();
            $request->category_image->storeAs('images/brands', $categoryImageName, 'public');
        }

        DB::beginTransaction();
        $brand = Brand::create([
            'title' => $request->title,
            'name' => $request->name,
            'description' => $request->description,
            'primary_image' => $request->has('primary_image') ? $primaryImageName : null,
            'brand_image' => $request->has('brand_image') ? $brandImageName : null,
            'slider_image' => $request->has('slider_image') ? $sliderImageName : null,
            'category_image' => $request->has('category_image') ? $categoryImageName : null,
            'iranian_designer' => $request->iranian_designer ?? 1,
            'is_watch' => $request->is_watch ?? 0,
            'status' => $request->status ?? 1,
        ]);
        DB::commit();
        return $this->succesResponse("brand created successfully",201, new AdminBrandResource($brand));
    }

    public function show(Brand $brand)
    {
        return $this->succesResponse("brand $brand->name", 200, new AdminBrandResource($brand));
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => 'required',
            // 'name' => 'required',
            'description' => 'nullable',
            'iranian_designer' => 'nullable',
            'primary_image' => 'nullable|image'
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('primary_image'))
        {
            $primaryImageName = Carbon::now()->microsecond . '.' . $request->primary_image->extension();
            $request->primary_image->storeAs('images/brands', $primaryImageName, 'public');
        }
        if($request->has('brand_image'))
        {
            $brandImageName = Carbon::now()->microsecond . '.' . $request->brand_image->extension();
            $request->brand_image->storeAs('images/brands', $brandImageName, 'public');
        }
        if($request->has('slider_image'))
        {
            $sliderImageName = Carbon::now()->microsecond . '.' . $request->slider_image->extension();
            $request->slider_image->storeAs('images/brands', $sliderImageName, 'public');
        }
        if($request->has('category_image'))
        {
            $categoryImageName = Carbon::now()->microsecond . '.' . $request->category_image->extension();
            $request->category_image->storeAs('images/brands', $categoryImageName, 'public');
        }

        DB::beginTransaction();
        $brand->update([
            'title' => $request->title ?? $brand->title,
            'name' => $request->name ?? $brand->name,
            'description' => $request->description ?? $brand->description,
            'primary_image' => $request->has('primary_image') ? $primaryImageName : $brand->primary_image,
            'brand_image' => $request->has('brand_image') ? $brandImageName : $brand->brand_image,
            'slider_image' => $request->has('slider_image') ? $sliderImageName : $brand->slider_image,
            'category_image' => $request->has('category_image') ? $categoryImageName : $brand->category_image,
            'iranian_designer' => $request->iranian_designer ?? $brand->iranian_designer,
            'is_watch' => $request->is_watch ?? $brand->is_watch,
            'status' => $request->status ?? $brand->status,
        ]);
        DB::commit();
        return $this->succesResponse("brand updated successfully",200, new AdminBrandResource($brand));
    }

    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        $brand->delete();
        DB::commit();
        return $this->succesResponse("brand deleted successfully",200, new AdminBrandResource($brand));
    }

    public function changebrandStatus(Request $request,Brand $brand)
    {
        $brand->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("brand status updated successfully",200, new AdminBrandResource($brand));
    }
}
