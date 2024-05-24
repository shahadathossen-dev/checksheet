<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public $guard_name = 'api';

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\DatabaseNotification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function mardAsRead(Request $request, DatabaseNotification $notification)
    {
        $notification->markAsRead();
        // return to_route('tasklists.show', $notification->data->id);
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Update the specified bulk resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

     /**
     * Delete the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAll(Request $request)
    {
        $user = User::find(auth()->id());
        $user->notifications()->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }

}
