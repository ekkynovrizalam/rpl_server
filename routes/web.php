<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified');

Route::get('/auth_to_slack', 'SlackController@authToSlack');

Route::get('/landing', 'SlackController@landing');

Route::get('/test', function(){
  $documentFiles = Storage::disk('local')->files('\\');
  foreach ($documentFiles as $key => $documentFile){

    if ($key != 0) {
    $path = Storage::disk('local')->get($documentFile);
    $file_ftp = Storage::disk('google')->put($documentFile, $path);
    }
  }
});