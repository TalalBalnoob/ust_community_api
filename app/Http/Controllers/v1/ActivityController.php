<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function getAllUserActivitis(Request $request)
    {
        $notifications = NotificationResource::collection($request->user()->notifications);

        return response()->json($notifications, 200);
    }


    public function getUnreadUserActivitis(Request $request)
    {
        $notifications = NotificationResource::collection($request->user()->unreadNotifications);

        return response()->json($notifications, 200);
    }

    public function readActivity(Request $request)
    {
        foreach ($request->user()->unreadNotifications as $notification) {
            if ($notification['id'] === $request->activityID) {
                $notification->markAsRead();
            }
        }

        return response()->json(200);
    }
}
