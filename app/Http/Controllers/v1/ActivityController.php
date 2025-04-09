<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function getAllUserActivitis(Request $request)
    {
        $notifications = $request->user()->notifications;

        return response()->json(['data' => $notifications], 200);
    }


    public function getUnreadUserActivitis(Request $request)
    {
        $notifications = $request->user()->unreadNotifications;

        return response()->json(['data' => $notifications], 200);
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
