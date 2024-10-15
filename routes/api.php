<?php

declare(strict_types=1);

use App\Http\Controllers\Api\InvoiceToPdfController;
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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/faktura/{invoice}/html', [InvoiceToPdfController::class, 'toHtml'])->name('api.invoices.to.html');
    Route::get('/faktura/{invoice}/pdf', [InvoiceToPdfController::class, 'toPdf'])->name('api.invoices.to.pdf');
});
