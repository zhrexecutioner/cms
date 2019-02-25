<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/users',UsersController::class);
    $router->resource('/wxuser',WeixinController::class);
    $router->resource('/wxmedia',WeixinMediaController::class);

    $router->get('/weixin/sendmsg','WeixinController@sendMsgView');      //
    $router->post('/weixin/sendmsg','WeixinController@sendMsg');

});
