<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class NotificationController extends Controller
{
    public function index(Request $request){
        $user = User::find(Crypt::decryptString($request->input('user_id')));
        // dd($user->notifications);
        $unread_notifications = $user->unreadNotifications()->count();
        return response()->json(['all_notifications' => $user->notifications, 'unread' => $unread_notifications]);
    }

    public function unreadNotifications(){
        $unread_notifications = auth()->user()->unreadNotifications()->count();

        return response()->json($unread_notifications);
    }


    public function markAsRead(Request $request){
        $notification = auth()->user()->notifications()->find($request->input('notification_id'));
        if ($notification) {
            $notification->markAsRead();
        }
        $unread_notifications = auth()->user()->unreadNotifications()->count();

        return response()->json($unread_notifications);
    }
    public function markAllAsRead(Request $request){
        $user = auth()->user();
        // dd($user->unreadNotifications);
        if ($user->unreadNotifications) {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }
        $unread_notifications = auth()->user()->unreadNotifications()->count();

        return response()->json($unread_notifications);
    }
}
