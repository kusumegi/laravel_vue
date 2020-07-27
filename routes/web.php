<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    logger()->info('ルートにアクセス');
    // return view('welcome');
    return redirect('/todo');
});

// テンプレート用のシンプルなアプリ
Route::get('/app1{any}', 'SpaController@app1')->where('any', '(/?$|/.*)');

// TODOリスト
Route::get('/todo{any}', 'SpaController@todo')->where('any', '(/?$|/.*)');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
