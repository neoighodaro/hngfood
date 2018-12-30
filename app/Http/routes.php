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
    $router->get('/callback/user', ['as' =>'auth.slack.callback.user', 'uses' => 'AuthController@handleProviderCallbackUser']);
    $router->get('/callback', 'AuthController@handleProviderCallback');
    $router->get('/', ['as' => 'auth.slack', 'uses' => 'AuthController@redirectToProvider']);
});


// ------------------------------------------------------------------------
// SLACK COMMANDS
// ------------------------------------------------------------------------

$router->group(['prefix' => 'slack/commands', 'namespace' => 'SlackCommands'], function ($router) {
    $router->group(['prefix' => 'wallet'], function ($router) {
        $router->post('balance', ['as' => 'slack.cmd.wallet.balance', 'uses' => 'WalletController@balance']);
    });
    
    $router->post('freelunch',['as' => 'slack.cmd.freelunch', 'uses' => 'FreeLunchController@give']);
});


// ------------------------------------------------------------------------
// ADMINISTRATION
// ------------------------------------------------------------------------

$router->group(['prefix' => 'admin', 'namespace' => 'Admin'], function ($router) {
    $router->get('/login', ['as' => 'admin.login', 'uses' => 'AuthController@authForm']);
    $router->post('/login', ['uses' => 'AuthController@authProcess']);

    $router->group(['prefix' => 'freelunch'], function ($router) {
        $router->post('/update', ['as' => 'admin.freelunch.update', 'uses' => 'FreelunchController@update']);
        $router->get('/overview', ['as' => 'admin.freelunch.overview', 'uses' => 'FreelunchController@overview']);
    });

    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('/manage', ['as' => 'admin.users.manage', 'uses' => 'UserController@userlist']);
        $router->post('/update', ['as' => 'admin.users.update', 'uses' => 'UserController@update']);
    });

    $router->group(['prefix' => 'inventory'], function ($router) {
        $router->get('/manage', ['as' => 'admin.inventory.manage', 'uses' => 'InventoryController@index']);
    });

    $router->get('/dashboard', ['as' => 'admin.dashboard.overview', 'uses' => 'AdminController@index']);
    $router->get('/', ['as' => 'admin.dashboard', function () { return redirect()->route('admin.dashboard.overview'); }]);
});


// ------------------------------------------------------------------------
// OTHER ROUTES
// ------------------------------------------------------------------------

$router->get('/login', function () { return redirect('/'); });
$router->get('/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout',]);
$router->get('/home', ['as' => 'home', 'uses' => 'HomeController@index',]);
$router->get('/order/completed/{id}', ['as' => 'order.completed', 'uses' => 'HomeController@orderCompleted']);
$router->get('/order/history', ['as' => 'order.history', 'uses' => 'HomeController@orderHistory']);
$router->post('/order/complete', ['as' => 'order', 'uses' => 'HomeController@order']);

// ------------------------------------------------------------------------
// HOME PAGE
// ------------------------------------------------------------------------

$router->get('/', ['as' => 'guest.home', 'uses' => 'GuestController@index']);

