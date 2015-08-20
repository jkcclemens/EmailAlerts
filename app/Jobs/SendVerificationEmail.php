<?php namespace App\Jobs;


use App\Email;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendVerificationEmail extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

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
                'verify_link' => url('/verifyemail/' . $email->id . '/' . $email->verification_key)
            ],
            function ($m) use ($email) {
                $m->to($email)->subject('Verify your email!');
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
