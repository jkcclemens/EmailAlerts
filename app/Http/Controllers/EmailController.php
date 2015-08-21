<?php namespace App\Http\Controllers;

use App\Email;
use App\Jobs\SendVerificationEmail;
use App\Notification;
use Auth;
use ContextIO;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmailController extends DefaultController {

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
        if ($data['signature'] != hash_hmac('sha256', $data['timestamp'] . $data['token'], env('CONTEXT_IO_SECRET'))) {
            return response(json_encode(['error' => 'Invalid signature']), 400);
        }
        /**
         * @var $email Email
         */
        $email = Email::whereEmail(Arr::get($data, 'message_data.addresses.from.email'))->first();
        if (is_null($email)) {
            return response(json_encode(['error' => 'Unknown email']), 404);
        }
        $notification = new Notification();
        if ($email->verified and $email->notifications()->count() < 1) {
            $contextIO = new ContextIO(env('CONTEXT_IO_KEY'), env('CONTEXT_IO_SECRET'));
            $message = $contextIO->getMessageBody(Arr::get($data, 'account_id'), [
                'label' => 0,
                'folder' => Arr::get($data, 'message_data.folders.0'),
                'message_id' => Arr::get($data, 'message_data.message_id'),
                'type' => 'text/plain'
            ]);
            $notification->data = Arr::get($message->getData(), 'bodies.0.content');
        }
        $notification->email_id = $email->id;
        $notification->subject = Arr::get($data, 'message_data.subject');
        $notification->save();
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

}
