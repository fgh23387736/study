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
    return view('welcome');
});

Route::get('hello', function() {
	return '鈜鉝';
});

Route::get('user/profile', function() {
	return 'my url ' . route('profile');
})->name('profile');

Route::get('user/{id}/profile', function($id) {
	$url = route('profile', ['id' => $id]);
	return $url;
})->name('profile');

Route::domain('{account}.learning.test')->group(function () {
	Route::get('user/{id}', function ($account, $Id)
	{
		return 'This is ' . $account . ' page of User ' . $id;
	});
});

Route::get('test/Route', function () {
	// 获取当前路由实例
	// $route = Route::current(); 
	// 获取当前路由名称
	$name = Route::currentRouteName();
	// 获取当前路由action属性
	$action = Route::currentRouteAction();

	return ' ' . $name . ',' . $action;
})->name('theUrl');