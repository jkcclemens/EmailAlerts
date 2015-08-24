<?php namespace App\Http\Controllers;

use Auth;

class NotificationController extends DefaultController {

    public function getNotifications() {
        return response(
            Auth::user()
                ->notifications()
                ->with('email')
                ->get()
                ->toJson(),
            200,
            ['Content-Type' => 'application/json']
        );
    }

}
