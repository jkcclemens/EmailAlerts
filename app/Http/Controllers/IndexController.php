<?php namespace App\Http\Controllers;

use Auth;

class IndexController extends DefaultController {

    public function showIndex() {
        return view(Auth::check() ? 'authed.index' : 'index')->with('user', Auth::user());
    }

}
