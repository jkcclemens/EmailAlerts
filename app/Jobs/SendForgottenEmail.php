<?php namespace App\Jobs;

use App\User;
use Mail;

class SendForgottenEmail extends Job {

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function handle() {
        $user = $this->user;
        Mail::send(
            'emails.forgot-password',
            [
                'email' => $user->email,
                'resetLink' => 'https://emailalerts.xyz/reset/' . $user->id . '/' . $user->generateResetKey()
            ],
            function ($m) use ($user) {
                $m->to($user->email)->subject('Reset your password!');
            }
        );
    }
}
