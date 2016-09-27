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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index');

    Route::get(   '/produkt',           'ProductController@index')->name(  'product.index');
    Route::get(   '/produkt/dodaj',     'ProductController@create')->name( 'product.create');
    Route::put(   '/produkt/dodaj',     'ProductController@store')->name(  'product.store');
    Route::delete('/produkt/{product}', 'ProductController@destroy')->name('product.destroy');

    Route::get(   '/faktura',            'InvoiceController@index')->name(  'invoices.index');
    Route::get(   '/faktura/dodaj',      'InvoiceController@create')->name( 'invoices.create');
    Route::put(   '/faktura/dodaj',      'InvoiceController@store')->name(  'invoices.store');
    Route::delete('/faktura/{invoices}', 'InvoiceController@destroy')->name('invoices.destroy');

    Route::get(   '/kontrahent',               'BuyerController@index')->name(  'buyer.index');
    Route::get(   '/kontrahent/dodaj',         'BuyerController@create')->name( 'buyer.create');
    Route::put(   '/kontrahent/dodaj',         'BuyerController@store')->name(  'buyer.store');
    Route::get(   '/kontrahent/{buyer}',       'BuyerController@edit')->name(  'buyer.edit');
    Route::post(  '/kontrahent/{buyer}',       'BuyerController@update')->name(  'buyer.update');
    Route::delete('/kontrahent/{buyer}',       'BuyerController@destroy')->name('buyer.destroy');

});
