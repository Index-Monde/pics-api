<?php

namespace App\Http\Controllers\API;

use App\Models\Collection;
use App\Http\Resources\Collection\CollectionResource;
use App\Http\Resources\Collection\CollectionCollection;
use App\Http\Requests\Collection\CollectionStoreRequest;
use Illuminate\Http\Request;


class CollectionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return new CollectionCollection(Collection::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request\Collection\CollectionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionStoreRequest $request)
    {
         $collection = Collection::create($request->all());
         return new CollectionResource($collection);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        if($collection->exists()){
            return new CollectionResource($collection);
        }
        return $this->sendError('Collection error', ['error' => 'Not found collection'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        $collection->update($request->all());
        return new CollectionResource($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return $this->sendResponse([],'Delete successfully');
    }
}
