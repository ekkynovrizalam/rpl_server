<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/push_event', 'SlackController@push_event');
Route::get('/event', 'SlackController@event');
Route::post('/help', 'SlackController@help');
Route::post('/report', 'SlackController@report');
Route::post('/regist', 'SlackController@regist');
Route::get('/selectDB/{kelas}/{start_date}/{finish_date}','ReportController@dailyReport');
