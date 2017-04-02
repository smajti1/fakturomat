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
    Route::get('/panel', 'PanelController@index')->name('panel');

    Route::get('firma',                   'CompanyController@index')->name('company.index');
    Route::get('firma/dodaj',             'CompanyController@create')->name('company.create');
    Route::put('firma/dodaj',             'CompanyController@store')->name('company.store');
    Route::get('firma/{company}/edytuj',  'CompanyController@edit')->name('company.edit');
    Route::post('firma/{company}/edytuj', 'CompanyController@update')->name('company.update');
    Route::delete('firma/{company}/usun', 'CompanyController@destroy')->name('company.destroy');

    Route::get('/produkt-lista', 'ProductController@jsonList')->name('product.json.list');
    Route::get('/firma-lista',   'CompanyController@jsonList')->name('company.json.list');

    Route::get(   '/produkt',                       'ProductController@index')->name('product.index');
    Route::get(   '/produkt/dodaj',                 'ProductController@create')->name('product.create');
    Route::put(   '/produkt/dodaj',                 'ProductController@store')->name('product.store');
    Route::get(   '/produkt/{product}/edytuj',      'ProductController@edit')->name('product.edit');
    Route::post(  '/produkt/{product}/edytuj',      'ProductController@update')->name('product.update');
    Route::delete('/produkt/{product}',             'ProductController@destroy')->name('product.destroy');

    Route::get(   '/faktura',                  'InvoiceController@index')->name('invoices.index');
    Route::get(   '/faktura/dodaj',            'InvoiceController@create')->name('invoices.create');
    Route::put(   '/faktura/dodaj',            'InvoiceController@store')->name('invoices.store');
    Route::get(   '/faktura/{invoice}/edytuj', 'InvoiceController@edit')->name('invoices.edit');
    Route::post(  '/faktura/{invoice}/edytuj', 'InvoiceController@update')->name('invoices.update');
    Route::delete('/faktura/{invoice}',        'InvoiceController@destroy')->name('invoices.destroy');

    Route::get(   '/kontrahent',               'BuyerController@index')->name(  'buyer.index');
    Route::get(   '/kontrahent/dodaj',         'BuyerController@create')->name( 'buyer.create');
    Route::put(   '/kontrahent/dodaj',         'BuyerController@store')->name(  'buyer.store');
    Route::get(   '/kontrahent/{buyer}',       'BuyerController@edit')->name(  'buyer.edit');
    Route::post(  '/kontrahent/{buyer}',       'BuyerController@update')->name(  'buyer.update');
    Route::delete('/kontrahent/{buyer}',       'BuyerController@destroy')->name('buyer.destroy');

});

Route::get('register',      'AccountWizardController@wizard')->name('register');
Route::get('konto/{step?}', 'AccountWizardController@wizard')->name('wizard.account');
Route::post('konto/{step}', 'AccountWizardController@wizardPost')->name('wizard.account.post');