<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::get('/', [LocationController::class, 'index'])->name('locations.index');
Route::post('/import', [LocationController::class, 'import'])->name('locations.import');
Route::get('/export', [LocationController::class, 'export'])->name('locations.export');
Route::get('/template', [LocationController::class, 'downloadTemplate'])->name('locations.template');
Route::post('/locations/print-batch', [LocationController::class, 'printBatch'])->name('locations.print_batch');
Route::post('/locations/bulk-delete', [LocationController::class, 'bulkDestroy'])->name('locations.bulk_delete');
Route::post('/locations/truncate', [LocationController::class, 'truncate'])->name('locations.truncate');


Route::resource('locations', LocationController::class)->except(['show']); 
