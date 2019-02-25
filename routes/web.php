<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //echo '<pre>';print_r($_SESSION);echo '</pree>';
    echo '<pre>';print_r($_COOKIE);echo '</pre>';
    //return view('welcome');
});

Route::get('/','Home\IndexController@index');

Route::get('/info',function(){
    phpinfo();
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/test','User\UserController@test');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');



// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>40300]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


//Route::match(['get','post'],'/test/abc','Test\TestController@abc');
Route::any('/test/abc','Test\TestController@abc');


Route::get('/view/test1','Test\TestController@viewTest1');
Route::get('/view/test2','Test\TestController@viewTest2');




//用户注册
Route::get('/user/reg','User\UserController@reg');
Route::post('/user/reg','User\UserController@doReg');

Route::get('/user/login','User\UserController@login');           //用户登录
Route::post('/user/login','User\UserController@doLogin');        //用户登录
Route::get('/user/center','User\UserController@center');        //个人中心

Route::get('/users/login','Users\UsersController@login');           //用户登录
Route::post('/users/login','Users\UsersController@dologin');
Route::get('/users/update','Users\UsersController@update');
Route::post('/users/update','Users\UsersController@doupdate');
Route::get('/movie','User\UserController@movie');

//模板引入静态文件
Route::get('/mvc/test1','Mvc\MvcController@test1');

Route::get('/mvc/bst','Mvc\MvcController@bst');


//Test
Route::get('/test/cookie1','Test\TestController@cookieTest1');
Route::get('/test/cookie2','Test\TestController@cookieTest2');
Route::get('/test/session','Test\TestController@sessionTest');
Route::get('/test/mid1','Test\TestController@mid1')->middleware('check.uid');        //中间件测试
Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');        //中间件测试



//购物车
//Route::get('/cart','Cart\IndexController@index')->middleware('check.uid');
Route::get('/cart','Cart\IndexController@index')->middleware('check.login.token');
Route::get('/cart/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');      //添加商品
Route::post('/cart/add2','Cart\IndexController@add2')->middleware('check.login.token');      //添加商品
Route::get('/cart/del/{goods_id}','Cart\IndexController@del')->middleware('check.login.token');      //删除商品
Route::get('/cart/del2/{goods_id}','Cart\IndexController@del2')->middleware('check.login.token');      //删除商品


//商品
Route::get('/goods/{goods_id}','Goods\IndexController@index');          //商品详情


//订单
Route::get('/order/add','Order\IndexController@add');           //下单
Route::get('/show','Goods\IndexController@list');

Route::get('/payTest/{oid}','Pay\AlipayController@test');         //测试
Route::get('/pay/o/{oid}','Pay\IndexController@order');         //订单支付
Route::post('/payNotify','Pay\AlipayController@notify');        //支付宝支付 通知回调

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/weixin/test','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');

Route::get('/weixin/create_menu','Weixin\WeixinController@createMenu');

Route::get('/form/show','Weixin\WeixinController@formShow');     //表单测试
Route::post('/form/test','Weixin\WeixinController@formTest');     //表单测试




Route::get('/test/405','Weixin\WeixinController@http405');     //获取永久素材列表


Route::get('/weixin/material/list','Weixin\WeixinController@materialList');     //获取永久素材列表
Route::get('/weixin/material/upload','Weixin\WeixinController@upMaterial');     //上传永久素材
Route::post('/weixin/material','Weixin\WeixinController@materialTest');     //创建菜单

//微信聊天
Route::get('/weixin/kefu/chat','Weixin\WeixinController@chatView');     //客服聊天
Route::get('/weixin/chat/get_msg','Weixin\WeixinController@getChatMsg');