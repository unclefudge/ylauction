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

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/signup', 'HomeController@index')->name('home')->middleware('guest');

// Logged in Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/test', function () {return view('test');});
    Route::get('/test2', function () {return view('test2');});

    // Admin
    Route::get('/data/auction-items', 'AdminController@getItems');
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/auction-live', 'AdminController@auctionLive');
    Route::get('/admin/auction-max', 'AdminController@auctionMax');
    Route::get('/admin/auction-report', 'AdminController@auctionReport');
    Route::get('/admin/auction-status/{status}', 'AdminController@auctionStatus');

    // Auction Items
    Route::get('/admin/item/{id}/del', 'AuctionItemController@destroy');
    Route::resource('/admin/item', 'AuctionItemController');

    // Auction
    Route::get('/data/auctions/item/{id}', 'AuctionController@getItem');
    Route::resource('/auctions', 'AuctionController');


});
