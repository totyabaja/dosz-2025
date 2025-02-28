<?php

use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;
use LivewireFilemanager\Filemanager\Http\Controllers\Files\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PublicPageController::class, 'home'])->name('public.home');

// Fix routok
Route::get('/jogsegely', [PublicPageController::class, 'jogsegely'])
    ->name('public.jogsegely');
//Route::get('/tok', [PublicPageController::class, 'tok'])
//    ->name('public.tok');
Route::get('/tok/{to_slug}', [PublicPageController::class, 'to'])
    ->where('to_slug', '[a-z0-9-]+')
    ->name('public.to');
Route::get('/tok/{to_slug}/hirek', [PublicPageController::class, 'to_hirek'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('slug', '[a-z0-9-]+')
    ->name('public.to.hirek');
Route::get('/tok/{to_slug}/hir/{slug}', [PublicPageController::class, 'to_hir'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('slug', '[a-z0-9-]+')
    ->name('public.to.hir');
Route::get('/tok/{to_slug}/rendezvenyek', [PublicPageController::class, 'to_rendezvenyek'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('slug', '[a-z0-9-]+')
    ->name('public.to.rendezvenyek');
Route::get('/tok/{to_slug}/rendezveny/{slug}', [PublicPageController::class, 'to_rendezveny'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('slug', '[a-z0-9-]+')
    ->name('public.to.rendezveny');

Route::get('/hirek', [PublicPageController::class, 'hirek'])->name('public.hirek');
Route::get('/hir/{slug}', [PublicPageController::class, 'hir'])->where('slug', '[a-z0-9-]+')->name('public.hir');
Route::get('/rendezvenyek', [PublicPageController::class, 'rendezvenyek'])->name('public.rendezvenyek');
Route::get('/rendezveny/{slug}', [PublicPageController::class, 'rendezveny'])->name('public.rendezveny');
Route::get('/gyik', [PublicPageController::class, 'gyik'])->name('public.gyik');
Route::get('/alt-ker', [PublicPageController::class, 'alt_ker'])->name('public.alt_ker');
Route::get('/alt-ker', [PublicPageController::class, 'alt_ker'])->name('public.alt_ker');
Route::get('/dokumentumok/{folder?}', [PublicPageController::class, 'dokumentumok'])
    ->where('folder', '[a-z0-9-]+')
    ->name('public.dokumentumok');
Route::get('/tok/{to_slug}/dokumentumok/{folder?}', [PublicPageController::class, 'to_dokumentumok'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('folder', '[a-z0-9-]+')
    ->name('public.to.dokumentumok');

// Dinamikus oldalak slug alapján
// Route::get('/titkarsag', [PublicPageController::class, 'titkarsag'])->name('public.page.titkarsag');
//Route::get('/kuldottgyules', [PublicPageController::class, 'kuldottgyules'])->name('public.page.kuldottgyules');
//Route::get('/dokok', [PublicPageController::class, 'dokok'])->name('public.page.dokok');
//Route::get('/tiszteletbeli-elnokok', [PublicPageController::class, 'tiszteletbeli_elnokok'])->name('public.page.tiszteletbeli-elnokok');
//Route::get('/dijazottak', [PublicPageController::class, 'dijazottak'])->name('public.page.dijazottak');

Route::get('/tok/{to_slug}/{slug}', [PublicPageController::class, 'to_show'])
    ->where('to_slug', '[a-z0-9-]+')
    ->where('slug', '[a-z0-9-]+')
    ->name('public.to.pages');
Route::get('/{slug}', [PublicPageController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('public.pages');

// Route::get('/', [PublicController::class, 'index']);
