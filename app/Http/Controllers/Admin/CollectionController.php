<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\CollectionResource;
use App\Models\ChildCategoryCollection;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CollectionController extends ApiController
{
    public function index(Request $request)
    {
        $per_page = $request->count ?? 5;
        $orderBy = $request->sortBy ?? 'id';
        $collections = Collection::orderBy($orderBy)
        ->where('name', 'LIKE', "%{$request->search}%")
        ->where('code', 'LIKE', "%{$request->search}%")
        ->paginate($per_page);
        return $this->succesResponse("all collections",200, [
            "collections" => CollectionResource::collection($collections),
            "links" => CollectionResource::collection($collections)->response()->getData()->links,
            "meta" => CollectionResource::collection($collections)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            'childCategories' => "required"
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $collection = Collection::create([
            "name" => $request->name,
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => $request->parent_category_id,
            "is_discounted" => $request->is_discounted ?? 0,
            "status" => $request->status ?? 1
        ]);
        if ($request->has('childCategories')) {
            foreach ($request->childCategories as $childCategory) {
                ChildCategoryCollection::create([
                    'collection_id' => $collection->id,
                    'child_category_id' => $childCategory
                ]);
            }
        }
        DB::commit();
        return $this->succesResponse(
            "collection created successfully",
            201, 
            new CollectionResource($collection)
        );
    }

    public function show(Collection $collection)
    {
        return $this->succesResponse(
            "collection $collection->name",
            200,
            new CollectionResource($collection)
        );
    }

    public function update(Request $request, Collection $collection)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string"],
            "parent_category_id" => "nullable|integer"
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();

        $collection->update([
            "name" => $request->name,
            "code" => "coll_" . Str::random(8),
            "parent_category_id" => $request->parent_category_id ?? $collection->parent_category_id,
            "is_discounted" => $request->is_discounted ?? $collection->is_discounted,
            "status" => $request->status ?? $collection->status
        ]);
        DB::commit();
        return $this->succesResponse(
            "collection updated successfully",
            200, 
            new CollectionResource($collection)
        );
    }

    public function destroy(Collection $collection)
    {
        DB::beginTransaction();
        $collection->delete();
        DB::commit();
        return $this->succesResponse("collection deleted successfully",200, new CollectionResource($collection));
    }

    public function changeCollectionStatus(Request $request, Collection $collection)
    {
        $collection->update([
            'status' => $request->status == 1 ? 0 : 1
        ]);
        return $this->succesResponse("collection status updated successfully",200, new CollectionResource($collection));
    }
}
