<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['auth:api']], function()
{
    Route::get('/faktura/{invoice}/html', 'Api\InvoiceToPdfController@toHtml')->name('api.invoices.to.html');
    Route::get('/faktura/{invoice}/pdf',  'Api\InvoiceToPdfController@toPdf')->name('api.invoices.to.pdf');
    Route::get('/faktura/{invoice}/pdf-footer',  'Api\InvoiceToPdfController@footer')->name('api.invoices.to.pdf.footer');
});
