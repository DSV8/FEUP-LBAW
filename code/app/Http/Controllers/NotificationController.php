<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function unreadNotifications(Request $request)
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;
        if($request->read === "true"){
            $user->unreadNotifications->markAsRead();
        }
        return response()->json($unreadNotifications, 200);
    }
}