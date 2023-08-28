<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\WeekstyleResource;
use App\Models\ProductWeekstyle;
use App\Models\Weekstyle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WeekstyleController extends ApiController
{
    public function index()
    {
        $ws = Weekstyle::paginate(20);
        return $this->succesResponse('weekstyle section', 200, [
            'weekstyle' => WeekstyleResource::collection($ws),
            'links' => WeekstyleResource::collection($ws)->response()->getData()->links,
            'meta' => WeekstyleResource::collection($ws)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $wsImageName = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('images/weekstyle', $wsImageName, 'public');
        DB::beginTransaction();
        $ws = Weekstyle::create([
            'image' => $wsImageName,
            'is_watch' => $request->has('is_watch') ? $request->is_watch : 0
        ]);
        if($request->has('product')) {
            foreach ($request->product as $product) {
                ProductWeekstyle::create([
                    'product_id' => $product['id'],
                    'weekstyle_id' => $ws->id
                ]);
            }
        }
        DB::commit();
        return $this->succesResponse('weekstyle created successfully', 201, new WeekstyleResource($ws));
    }

    public function show(Weekstyle $weekstyle)
    {
        $ws = Weekstyle::find($weekstyle->id);
        return $this->succesResponse('weekstyle created successfully', 201, new WeekstyleResource($ws));
    }

    public function update(Request $request, Weekstyle $weekstyle)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        if($request->has('image')) {
            $wsImageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/weekstyle', $wsImageName, 'public');
        }
        $wsImageName = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('images/weekstyle', $wsImageName, 'public');
        DB::beginTransaction();
        $weekstyle->update([
            'image' => $request->has('image') ? $wsImageName : $weekstyle->image,
            'is_watch' => $request->has('is_watch') ? $request->is_watch : $weekstyle->is_watch
        ]);
        DB::commit();
        return $this->succesResponse('weekstyle updated successfully', 200, new WeekstyleResource($weekstyle));
    }

    public function destroy(Weekstyle $weekstyle)
    {
        DB::beginTransaction();
        $weekstyle->delete();
        DB::commit();
        return $this->succesResponse(
            'weekstyle deleted successfully',
             200,
             new WeekstyleResource($weekstyle)
        );
    }
}
