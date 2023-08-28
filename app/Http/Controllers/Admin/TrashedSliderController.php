<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashedSliderController extends ApiController
{
    public function trashedColors()
    {
        $sliders = DB::table('sliders')->whereNotNull('deleted_at')->paginate(10);
        return $this->succesResponse("admin trashed sliders",200, [
            "sliders" => SliderResource::collection($sliders),
            "links" => SliderResource::collection($sliders)->response()->getData()->links,
            "meta" => SliderResource::collection($sliders)->response()->getData()->meta,
        ]);
    }

    public function restoreColor($id)
    {
        Slider::where('id', $id)->withTrashed()->restore();
        return $this->succesResponse("slider is restored successfully",200, "");
    }

    public function forceDeleteColor($id)
    {
        Slider::where('id', $id)->withTrashed()->forceDelete();
        return $this->succesResponse("slider force deleted successfully",200, "");
    }
}
