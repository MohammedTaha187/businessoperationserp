<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Notification\StoreNotificationRequest;
use App\Http\Requests\Api\V1\Notification\UpdateNotificationRequest;
use App\Http\Resources\Api\V1\NotificationResource;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);

        return $this->paginatedResponse($notifications, __('lang.Notifications retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create($request->validated());

        return $this->successResponse(new NotificationResource($notification), __('lang.Notification created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        return $this->successResponse(new NotificationResource($notification), __('lang.Notification retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        $notification->update($request->validated());

        return $this->successResponse(new NotificationResource($notification), __('lang.Notification updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return $this->successResponse(null, __('lang.Notification deleted successfully'));
    }
}
