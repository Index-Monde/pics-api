<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationCollection;
use App\Http\Requests\Notification\NotificationStoreRequest;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new NotificationCollection(Notification::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationStoreRequest $request)
    {
        $notification = Notification::create($request->all());
        return new NotificationResource($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        if ($notification->exists()) {
            return new NotificationResource($notification);
        }
        return $this->sendError('Notification error', ['error' => 'Not found notification'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $notification->update($request->all());
        return new NotificationResource($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return $this->sendResponse([],'Notification user matched');
    }
}
