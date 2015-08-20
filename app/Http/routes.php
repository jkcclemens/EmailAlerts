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

$app->post('/signup', ['uses' => 'LoginController@signUp']);
$app->post('/login', ['uses' => 'LoginController@logIn']);

$app->group(['middleware' => 'auth'], function (\Laravel\Lumen\Application $app) {
    $app->post('/addemail', ['uses' => '\App\Http\Controllers\EmailController@addEmail']);
    $app->get('/verifyemail/{id}/{verificationKey}', ['uses' => '\App\Http\Controllers\EmailController@verifyEmail']);
    $app->get('/removeemail/{id}', ['uses' => '\App\Http\Controllers\EmailController@removeEmail']);
});
