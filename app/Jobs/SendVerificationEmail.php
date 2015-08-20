<?php namespace App\Jobs;


use App\Email;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;

class SendVerificationEmail extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    private $email;

    public function __construct(Email $email) {
        $this->email = $email;
    }

    public function handle(Mailer $mailer) {
        $email = $this->email;
        $mailer->send('emails.verify_email', [
            'email' => $email->email,
            'verify_link' => 'http://localhost:8000/verifyemail/' . $email->id . '/' . $email->verification_key // TODO: Real URL
        ], function ($m) use ($email) {
            $m->to($email)->subject('Verify your email!');
        });
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
