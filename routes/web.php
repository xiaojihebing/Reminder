<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
// Route::get('/check', 'LantouziController@index');
// Route::get('/go', 'LantouziController@index');

Route::group(['middleware' => 'auth', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
    Route::get('/', 'HomeController@index');
    // Route::get('products', 'ProductController@index');
    Route::resource('lantouzi', 'ArticleController');
    Route::resource('jingdong', 'JingdongController');
    Route::resource('rfq', 'RfqController');

});

