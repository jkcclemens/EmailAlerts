<?php namespace App\Http\Controllers;

use App\Email;
use App\Jobs\SendForgottenEmail;
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
            'password' => 'required'
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

    public function showChangePassword() {
        $this->setTitle('Change password');
        return view('authed.change-password');
    }

    public function changePassword(Request $request) {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|different:old_password|min:12'
        ]);
        $oldPassword = $request->request->get('old_password');
        $newPassword = $request->request->get('new_password');
        if (!Auth::validate(['email' => Auth::user()->email, 'password' => $oldPassword])) {
            return redirect()->back()->withErrors(['Invalid old password.']);
        }
        Auth::user()->password = bcrypt($newPassword);
        return redirect('/')->with(['message' => 'Password changed.']);
    }

    public function showForgotPassword() {
        $this->setTitle('Forgot your password?');
        return view('forgot-password');
    }

    public function forgotPassword(Request $request) {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $user = User::whereEmail($request->request->get('email'))->first();
        if (is_null($user)) {
            return redirect()->back()->withErrors(['No user with that primary email.']);
        }
        $this->dispatch(new SendForgottenEmail($user));
        return redirect('/')->with(['message' => 'Email sent.']);
    }

    public function showResetPassword($id, $reset_key) {
        $this->setTitle('Reset password');
        return view('reset-password')->with(['id' => $id, 'reset_key' => $reset_key]);
    }

    public function resetPassword(Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'new_password' => 'required|min:12',
            'reset_key' => 'required|size:16'
        ]);
        /**
         * @var $user User
         */
        $user = User::find($request->request->get('id'));
        if (is_null($user)) {
            return redirect('/')->withErrors(['No such user.']);
        }
        if ($user->reset_key != $request->request->get('reset_key')) {
            return redirect('/')->withErrors(['Invalid reset key.']);
        }
        $user->password = bcrypt($request->request->get('new_password'));
        $user->reset_key = null;
        $user->save();
        return redirect('/')->with(['message' => 'Password changed.']);
    }

}
