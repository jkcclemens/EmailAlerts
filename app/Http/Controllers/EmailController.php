<?php namespace App\Http\Controllers;

use App\Email;
use App\Jobs\PushJob;
use App\Jobs\SendVerificationEmail;
use App\Notification;
use Auth;
use ContextIO;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmailController extends DefaultController {

    const PREFIXES = [
        'Fwd:',
        'Forward:',
        'Fw:'
    ];
    const EXPECTED_KEYS = [
        'signature',
        'timestamp',
        'token',
        'message_data.addresses.from.email',
        'message_data.addresses.to.0.email',
        'message_data.folders.0',
        'message_data.message_id',
        'message_data.subject'
    ];

    public function addEmail(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|unique:emails,email,NULL,id,verified,1|unique:emails,email,NULL,id,user_id,' . Auth::user()->id
        ]);
        $email = new Email();
        $email->email = $request->get('email');
        $email->user_id = Auth::user()->id;
        $email->save();
        $this->dispatch(new SendVerificationEmail($email));
        return redirect()->back();
    }

    public function removeEmail($id) {
        /**
         * @var $email Email
         */
        $email = Email::find($id);
        if ($email->isPrimary() and $email->user->emails()->count() < 2) {
            return redirect('/')->withErrors(['Cannot delete your only email!']);
        }
        $email->delete();
        if ($email->isPrimary()) {
            $email->user->email = $email->user->emails[0]->email;
            $email->user->save();
        }
        return redirect('/');
    }

    public function verifyEmail($id, $verificationKey) {
        /**
         * @var $email Email
         */
        $email = Email::whereId($id)->whereVerificationKey($verificationKey)->first();
        if (is_null($email)) {
            return redirect('/')->withErrors(['Invalid email verification URL.']);
        }
        $email->verified = true;
        $email->save();
        return redirect('/');
    }

    public function makePrimaryEmail($id) {
        /**
         * @var $email Email
         */
        $email = Email::find($id);
        if (is_null($email)) {
            return redirect('/')->withErrors(['Invalid email.']);
        }
        if ($email->user_id != Auth::user()->id) {
            return redirect('/')->withErrors(['That is not your email.']);
        }
        if (!$email->verified) {
            return redirect('/')->withErrors(['Please verify that email before making it primary.']);
        }
        $email->user->email = $email->email;
        $email->user->save();
        return redirect('/');
    }

    public function receive(Request $request) {
        $data = $request->json()->all();
        foreach (EmailController::EXPECTED_KEYS as $expected) {
            if (is_null(Arr::get($data, $expected))) {
                return response(json_encode(['error' => 'Invalid data']), 400, ['Content-Type' => 'application/json']);
            }
        }
        if ($data['signature'] != hash_hmac('sha256', $data['timestamp'] . $data['token'], env('CONTEXT_IO_SECRET'))) {
            return response(json_encode(['error' => 'Invalid signature']), 400, ['Content-Type' => 'application/json']);
        }
        $isForward = $this->isGmailForwardEmail($data);
        $body = null;
        if ($isForward) {
            $body = $this->downloadBody($data);
            $email = explode(' ', $body)[0];
        } else {
            // TODO: One day, support multiple to addresses and alert all verified addresses (once per account)
            $email = Arr::get($data, 'message_data.addresses.to.0.email');
        }
        // TODO: Make a different email for sending push notifications? Forwards keep the original message exactly as it
        // was. So, the forwarded message isn't from the account that initially received it, it's from the original
        // sender.
        /**
         * @var $email Email
         */
        $email = Email::whereEmail($email)->first();
        if (is_null($email)) {
            return response(json_encode(['error' => 'Unknown email']), 404, ['Content-Type' => 'application/json']);
        }
        if (!$email->user->pb_access_token) {
            return response(json_encode(['error' => 'Pushbullet not authenticated.']), 400, ['Content-Type' => 'application/json']);
        }
        if (!$email->verified) {
            return response(json_encode(['error' => 'Unverified email'], 400, ['Content-Type' => 'application/json']));
        }
        $notification = new Notification();
        if ($email->notifications()->count() < 1) {
            $contextIO = new ContextIO(env('CONTEXT_IO_KEY'), env('CONTEXT_IO_SECRET'));
            $message = $contextIO->getMessageBody(Arr::get($data, 'account_id'), [
                'label' => 0,
                'folder' => Arr::get($data, 'message_data.folders.0'),
                'message_id' => Arr::get($data, 'message_data.message_id'),
                'type' => 'text/plain'
            ]);
            $notification->data = Arr::get($message->getData(), 'bodies.0.content');
        } else {
            $notification->data = null;
        }
        $notification->email_id = $email->id;
        $notification->subject = $this->replaceForwardPrefix(Arr::get($data, 'message_data.subject'));
        $notification->save();
        $this->dispatch(new PushJob($email->user->pb_access_token, $notification->subject));
        return response(json_encode(['status' => 'received']), 200, ['Content-Type' => 'application/json']);
    }

    public function makePrimary($id) {
        /**
         * @var $email Email
         */
        $email = Email::find($id);
        if (is_null($email)) {
            return redirect('/')->withErrors(['Invalid email.']);
        }
        Auth::user()->email = $email->email;
        Auth::user()->save();
        return redirect('/');
    }

    private function replaceForwardPrefix($subject) {
        foreach (EmailController::PREFIXES as $prefix) {
            $prefix .= ' ';
            if (stripos($subject, $prefix) !== false) {
                return preg_replace('/' . $prefix . '/i', '', $subject, 1);
            }
        }
        return $subject;
    }

    private function isGmailForwardEmail($data) {
        return Arr::get($data, 'message_data.addresses.from.email') == "forwarding-noreply@google.com";
    }

    private function downloadBody($data) {
        $contextIO = new ContextIO(env('CONTEXT_IO_KEY'), env('CONTEXT_IO_SECRET'));
        $message = $contextIO->getMessageBody(Arr::get($data, 'account_id'), [
            'label' => 0,
            'folder' => Arr::get($data, 'message_data.folders.0'),
            'message_id' => Arr::get($data, 'message_data.message_id'),
            'type' => 'text/plain'
        ]);
        return Arr::get($message->getData(), 'bodies.0.content');
    }

}
