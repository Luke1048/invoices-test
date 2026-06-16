<?php

use App\Http\Controllers\CreateInvoiceController;
use App\Http\Controllers\GetInvoiceItemController;
use App\Http\Controllers\GetInvoiceListController;
use App\Http\Controllers\UpdateInvoiceItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('invoices')->group(function () {
    Route::get('', GetInvoiceListController::class);
    Route::get('{id}', GetInvoiceItemController::class);
    Route::post('', CreateInvoiceController::class);
    Route::put('{id}', UpdateInvoiceItemController::class);
});



