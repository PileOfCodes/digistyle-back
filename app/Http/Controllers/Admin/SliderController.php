<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\SliderResource;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SliderController extends ApiController
{
    public function index()
    {
        $sliders = Slider::paginate(10);
        return $this->succesResponse("all sliders",200, [
            "sliders" => SliderResource::collection($sliders),
            "links" => SliderResource::collection($sliders)->response()->getData()->links,
            "meta" => SliderResource::collection($sliders)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "image" => "required|image",
            "collection_id" => "required|integer"
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
        $request->image->storeAs('images/slider',$imageName,'public');
        DB::beginTransaction();

        $slider = Slider::create([
            "image" => $imageName,
            "collection_id" => $request->collection_id
        ]);
        DB::commit();
        return $this->succesResponse(
            "slider created successfully",
            201, 
            new SliderResource($slider)
        );
    }

    public function show(Slider $slider)
    {
        return $this->succesResponse(
            "slider $slider->name",
            200,
            new SliderResource($slider->load('collection')->load('category'))
        );
    }

    public function update(Request $request, Slider $slider)
    {
        $validator = Validator::make($request->all(), [
            "image" => "nullable|image",
            "collection_id" => "required|integer"
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        if($request->has('image'))
        {
            $imageName = Carbon::now()->microsecond .'.'. $request->image->extension();
            $request->image->storeAs('images/slider',$imageName,'public');
        }
        DB::beginTransaction();

        $slider->update([
            "image" => $request->has('image') ? $imageName : $slider->image,
            "collection_id" => $request->collection_id ?? $slider->collection_id
        ]);
        DB::commit();
        return $this->succesResponse(
            "slider updated successfully",
            200, 
            new SliderResource($slider)
        );
    }

    public function destroy(Slider $slider)
    {
        DB::beginTransaction();
        $slider->delete();
        DB::commit();
        return $this->succesResponse("slider deleted successfully",200, new SliderResource($slider));
    }

    public function changeSliderStatus(Request $request, Slider $slider)
    {
        $slider->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("slider status updated successfully",200, new SliderResource($slider));
    }
}
