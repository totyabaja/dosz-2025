<?php

use Illuminate\Support\Facades\Route;

Route::middleware(config('totyadev-media-manager.api.middlewares'))->prefix(config('totyadev-media-manager.api.prefix'))->name('media-manager.')->group(function () {
    Route::get('/folders', [\TotyaDev\TotyaDevMediaManager\Http\Controllers\FolderController::class, 'index'])->name('folders.index');
    Route::get('/folders/{id}', [\TotyaDev\TotyaDevMediaManager\Http\Controllers\FolderController::class, 'show'])->name('folders.show');
});
