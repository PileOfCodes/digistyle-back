<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminFilterResource;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminFilterController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $sort_by = $request->sortBy ?? 'id';
        if($request->has('search'))
        {
            $filters = Filter::orderBy($sort_by)
            ->where('name', 'LIKE', "%{$request->search}%")
            ->paginate($per_page);
        }else {
            $filters = Filter::orderBy($sort_by)->paginate($per_page);
        } 
        return $this->succesResponse("all admin fitlers", 200, [
            "fitlers" => AdminFilterResource::collection($filters),
            "links" => AdminFilterResource::collection($filters)->response()->getData()->links,
            "meta" => AdminFilterResource::collection($filters)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }

        DB::beginTransaction();
        $filter = Filter::create([
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);
        DB::commit();
        return $this->succesResponse("fitler created successfully",201, new AdminFilterResource($filter));
    }

    public function show(Filter $filter)
    {
        return $this->succesResponse("filter $filter->name", 200, new AdminFilterResource($filter));
    }

    public function update(Request $request, Filter $filter)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()) 
        {
            return $this->errorResponse($validator->messages(),422);
        }

        DB::beginTransaction();
        $filter->update([
            'name' => $request->name ?? $filter->name,
            'status' => $request->status ?? $filter->status,
        ]);
        DB::commit();
        return $this->succesResponse("filter updated successfully",200, new AdminFilterResource($filter));
    }

    public function destroy(Filter $filter)
    {
        DB::beginTransaction();
        $filter->delete();
        DB::commit();
        return $this->succesResponse("filter deleted successfully",200, new AdminFilterResource($filter));
    }

    public function changebrandStatus(Request $request,Filter $filter)
    {
        $filter->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("filter status updated successfully",200, new AdminFilterResource($filter));
    }
}
