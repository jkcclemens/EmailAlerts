<?php namespace App\Http\Controllers;

use App\Email;
use App\Jobs\SendVerificationEmail;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Validator;

class LoginController extends DefaultController {

    public function showSignUpPage() {
        $this->setTitle('Sign up');
        return view('sign-up');
    }

    public function signUp(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|different:password|email|unique:users,email',
            'password' => 'required|min:12'
        ]);
        if ($validator->fails()) {
            return redirect('/signup')
                ->withErrors($validator)
                ->withInput();
        }
        $user = new User();
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();
        $email = new Email();
        $email->user_id = $user->id;
        $email->email = $user->email;
        $email->verified = false;
        $email->save();
        $this->dispatch(new SendVerificationEmail($email));
        return redirect('/')->with('message', 'Successfully registered. Please check your email for a verification link.');
    }

    public function showLogInPage() {
        $this->setTitle('Log in');
        return view('log-in');
    }

    public function logIn(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:12'
        ]);
        if ($validator->fails()) {
            return redirect('/login')
                ->withErrors($validator)
                ->withInput();
        }
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            return redirect('/');
        }
        $mb = new MessageBag();
        $mb->add(0, 'Invalid username or password.');
        return redirect('/login')
            ->withInput()
            ->with('errors', $mb);
    }

    public function logOut() {
        Auth::logout();
        return redirect()->back();
    }

}
