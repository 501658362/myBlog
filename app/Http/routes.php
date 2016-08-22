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

get('/', function () {
    return view('welcome');
});

//Route::get('/', function () {
//    return redirect('/blog');
//});

Route::group(['namespace'=>'Web'],function(){
    Route::get('blog', 'BlogController@index');
    Route::get('blog/{slug}', 'BlogController@show');
});

// Admin area
Route::get('mis', function () {
    return redirect('/mis/post');
});

Route::group(['namespace' => 'Mis', "prefix"=>'mis','middleware' => 'auth'], function () {
    resource('post', 'PostController', ['except' => 'show']);
    resource('tag', 'TagController', ['except' => 'show']);
    get('upload', 'UploadController@index');
    post('upload/file', 'UploadController@uploadFile');
    delete('upload/file', 'UploadController@deleteFile');
    post('upload/folder', 'UploadController@createFolder');
    delete('upload/folder', 'UploadController@deleteFolder');
});



Route::group(['middleware'=>'auth'],function(){

    Route::group(['middleWare' => ['test']], function () {
        Route::resource('test', 'TestController');
    });
    Route::get('test/{id}', [
        'middleware' => 'test',
        function () {
            dd(1);
        }
    ]);

    // 个人中心
    Route::group(['namespace' => 'Mis'], function () {
        Route::resource('profile', 'ProfileController');
    });

});
Route::group(['namespace' => 'Auth'],function(){
    // 认证路由...
    Route::get('auth/login', 'AuthController@getLogin');
    Route::post('auth/login', 'AuthController@postLogin');
    Route::get('auth/logout', 'AuthController@getLogout');
    // 注册路由...
    Route::get('auth/register', 'AuthController@getRegister');
    Route::post('auth/register', 'AuthController@postRegister');

    // 密码重置链接请求路由...
    Route::get('auth/email', 'PasswordController@getEmail');
    Route::post('auth/email', 'PasswordController@postEmail');
    // 密码重置路由...
    Route::get('password/reset/{token}', 'PasswordController@getReset');
    Route::post('password/reset', 'PasswordController@postReset');
});

// 联系我们
Route::resource('contact','ContactController',['only' => ['index','store']]);

