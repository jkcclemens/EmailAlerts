<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', ['uses' => 'IndexController@showIndex']);
$app->get('/signup', ['uses' => 'LoginController@showSignUpPage']);
$app->get('/login', ['uses' => 'LoginController@showLogInPage']);
$app->get('/logout', ['uses' => 'LoginController@logOut']);
$app->get('/forgot', ['uses' => 'LoginController@showForgotPassword']);
$app->get('/reset/{id}/{reset_key}', ['uses' => 'LoginController@showResetPassword']);

$app->post('/signup', ['uses' => 'LoginController@signUp']);
$app->post('/login', ['uses' => 'LoginController@logIn']);
$app->post('/receive', ['uses' => 'EmailController@receive']);
$app->post('/forgot', ['uses' => 'LoginController@forgotPassword']);
$app->post('/reset', ['uses' => 'LoginController@resetPassword']);

$app->group(['middleware' => 'auth'], function (\Laravel\Lumen\Application $app) {
    $app->post('/addemail', ['uses' => '\App\Http\Controllers\EmailController@addEmail']);
    $app->post('/changepassword', ['uses' => '\App\Http\Controllers\LoginController@changePassword']);
    $app->get('/changepassword', ['uses' => '\App\Http\Controllers\LoginController@showChangePassword']);
    $app->get('/verifyemail/{id}/{verificationKey}', ['uses' => '\App\Http\Controllers\EmailController@verifyEmail']);
    $app->get('/removeemail/{id}', ['uses' => '\App\Http\Controllers\EmailController@removeEmail']);
    $app->get('/makeprimary/{id}', ['uses' => '\App\Http\Controllers\EmailController@makePrimary']);
    $app->get('/pb_auth', ['uses' => '\App\Http\Controllers\PushbulletController@authResult']);
    $app->get('/unlink_pushbullet', ['uses' => '\App\Http\Controllers\PushbulletController@unlink']);
});

$app->get('/bcrypt/{something}', function($something) {
    return bcrypt($something);
});
