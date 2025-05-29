<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::get('/data', [ContactController::class, 'getContacts'])->name('data');
    Route::post('/', [ContactController::class, 'store'])->name('store');
    Route::get('/{id}', [ContactController::class, 'show'])->name('show');
    Route::put('/{id}', [ContactController::class, 'update'])->name('update');
    Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');

    Route::post('/merge/data', [ContactController::class, 'getMergeData'])->name('merge.data');
    Route::post('/merge', [ContactController::class, 'mergeContacts'])->name('merge');
});
