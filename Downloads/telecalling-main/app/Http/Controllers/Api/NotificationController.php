<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotification(Request $request)
    {
        $technician = Auth::guard('technician-api')->user();
        if (!$technician) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $notification = NotificationMessage::with('technician:id,first_name,last_name')->where('technician_id', $technician->id)->orderBy('created_at', 'desc')->get(['id', 'technician_id','title', 'body', 'status', 'created_at']);
        if ($notification->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully get notification',
            'notification' => $notification
        ], 200);
    }

    public function markAllNotificationsAsSeen(Request $request)
    {
        $technician = Auth::guard('technician-api')->user();
        if (!$technician) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        NotificationMessage::where('technician_id', $technician->id)
            ->where('status', 0)
            ->update(['status' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as seen',
        ], 200);
    }

}
