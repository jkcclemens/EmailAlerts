<?php namespace App\Http\Controllers;

use Auth;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class PushbulletController extends DefaultController {

    function authResult(Request $request) {
        if ($request->get('error')) {
            return redirect('/')->withErrors(['You chose not to link your Pushbullet account!']);
        }
        $code = $request->query->get('code');
        $client = new Client();
        $data = json_decode($client->post('https://api.pushbullet.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('PUSHBULLET_CLIENT_ID'),
                'client_secret' => env('PUSHBULLET_CLIENT_SECRET'),
                'code' => $code
            ]
        ])->getBody()->getContents());
        Auth::user()->pb_access_token = $data['access_token'];
        Auth::user()->save();
        return redirect('/');
    }

}
