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

Route::get("/poker", function(){
    return View::make('main');
});
Route::post('/deal', 'PokerController@deal' );
Route::post('/shuffle', 'PokerController@shuffle' );