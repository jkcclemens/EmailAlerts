<?php namespace App\Jobs;

use App\Email;
use Mail;

class SendVerificationEmail extends Job {

    private $email;

    public function __construct(Email $email) {
        $this->email = $email;
    }

    public function handle() {
        $email = $this->email;
        Mail::send(
            'emails.verify_email',
            [
                'email' => $email->email,
                'verify_link' => 'https://emailalerts.xyz/verifyemail/' . $email->id . '/' . $email->verification_key
            ],
            function ($m) use ($email) {
                $m->to($email->email)->subject('Verify your email!');
            }
        );
    }
}
