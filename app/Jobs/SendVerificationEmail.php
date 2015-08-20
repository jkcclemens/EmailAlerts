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
                'verify_link' => 'https://emailalerts.royaldev.org/verifyemail/' . $email->id . '/' . $email->verification_key // TODO: Update
            ],
            function ($m) use ($email) {
                $m->to($email->email)->subject('Verify your email!');
            }
        );
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire() {
        // TODO: Implement fire() method.
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody() {
        // TODO: Implement getRawBody() method.
    }
}
