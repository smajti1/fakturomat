<?php

declare(strict_types=1);

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

use App\Http\Controllers\AccountWizardController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Settings\CompanyInvoiceNumberController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], static function () {
    Route::get('/panel', [PanelController::class, 'index'])->name('panel');

    Route::get('firma/edytuj',  [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('firma/edytuj', [CompanyController::class, 'update'])->name('company.update');

    Route::get('/produkt-lista', [ProductController::class, 'jsonList'])->name('product.json.list');
    Route::get('/firma-lista',   [CompanyController::class, 'jsonList'])->name('company.json.list');

    Route::get(   '/produkt',                       [ProductController::class, 'index'])->name('product.index');
    Route::get(   '/produkt/dodaj',                 [ProductController::class, 'create'])->name('product.create');
    Route::put(   '/produkt/dodaj',                 [ProductController::class, 'store'])->name('product.store');
    Route::get(   '/produkt/{product}/edytuj',      [ProductController::class, 'edit'])->name('product.edit');
    Route::post(  '/produkt/{product}/edytuj',      [ProductController::class, 'update'])->name('product.update');
    Route::delete('/produkt/{product}',             [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get(   '/faktura',                  [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get(   '/faktura/dodaj',            [InvoiceController::class, 'create'])->name('invoices.create');
    Route::put(   '/faktura/dodaj',            [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get(   '/faktura/{invoice}/edytuj', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post(  '/faktura/{invoice}/edytuj', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/faktura/{invoice}',        [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::get(   '/kontrahent',               [BuyerController::class, 'index'])->name('buyer.index');
    Route::get(   '/kontrahent/dodaj',         [BuyerController::class, 'create'])->name('buyer.create');
    Route::put(   '/kontrahent/dodaj',         [BuyerController::class, 'store'])->name('buyer.store');
    Route::get(   '/kontrahent/{buyer}',       [BuyerController::class, 'edit'])->name('buyer.edit');
    Route::post(  '/kontrahent/{buyer}',       [BuyerController::class, 'update'])->name('buyer.update');
    Route::delete('/kontrahent/{buyer}',       [BuyerController::class, 'destroy'])->name('buyer.destroy');

    Route::group(['namespace' => 'Settings', 'prefix' => 'ustawienia'], static function () {
        Route::get('/numeracja-faktur/edytuj', [CompanyInvoiceNumberController::class, 'index'])->name('settings.company_invoice_number.edit');
        Route::post('/numeracja-faktur/edytuj', [CompanyInvoiceNumberController::class, 'update'])->name('settings.company_invoice_number.update');
    });

});

Route::get('register',      [AccountWizardController::class, 'wizard'])->name('register');
Route::get('konto/{step?}', [AccountWizardController::class, 'wizard'])->name('wizard.account');
Route::post('konto/{step}', [AccountWizardController::class, 'wizardPost'])->name('wizard.account.post');
