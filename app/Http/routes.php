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

// ------------------------------------------------------------------------
// SLACK AUTHENTICATION!
// ------------------------------------------------------------------------

$router->group(['prefix' => 'auth/slack', 'namespace' => 'Auth'], function ($router) {
    $router->get('/callback', 'AuthController@handleProviderCallback');
    $router->get('/', ['as' => 'auth.slack', 'uses' => 'AuthController@redirectToProvider']);
});

// ------------------------------------------------------------------------
// OTHER ROUTES
// ------------------------------------------------------------------------

$router->get('/login', function () {
    return redirect('/');
});

$router->get('/logout', [
    'as'   => 'logout',
    'uses' => 'Auth\AuthController@logout',
]);

$router->get('/home', [
    'as'   => 'home',
    'uses' => 'HomeController@index',
]);

$router->get('/order/completed/{id}', [
    'as'   => 'order.completed',
    'uses' => 'HomeController@orderCompleted'
]);

$router->get('/order/history', [
    'as'   => 'order.history',
    'uses' => 'HomeController@orderHistory'
]);

$router->post('/order/complete', [
    'as'   => 'order',
    'uses' => 'HomeController@order'
]);

// ------------------------------------------------------------------------
// HOME PAGE
// ------------------------------------------------------------------------

$router->get('/', function () {
    if (auth()->user()) {
        return redirect(route('home'));
    }

    return view('welcome');
});

