<?php namespace App\Http\Controllers;

use App\Email;
use Auth;
use Illuminate\Http\Request;

class EmailController extends DefaultController {

    public function addEmail(Request $request) {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $email = new Email();
        $email->email = $request->get('email');
        $email->user_id = Auth::user()->id;
        $email->save();
        return redirect()->back();
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

}
