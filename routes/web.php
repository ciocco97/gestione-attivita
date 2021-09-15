<?php

use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\ActivityController;
use \App\Http\Middleware\MyAuth;
use \App\Http\Middleware\Language;
use \App\Http\Controllers\AjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/user/login');
Route::get('/lang/{lang}/change', [LangController::class, 'change'])->name('lang.change');

Route::get('/prova', [TrialController::class, 'trialFunction']);

Route::middleware([Language::class])->group(function () {
    Route::get('/user/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/user/authentication', [AuthController::class, 'authentication'])->name('user.authentication');
    Route::get('/user/logout', [AuthController::class, 'logout'])->name('user.logout');


    Route::middleware([MyAuth::class])->group(function () {
        Route::resource('activity', ActivityController::class);
        Route::post('/activity/{id}/update', [ActivityController::class, 'update'])->name('activity.update');
        Route::get('/activity/{id}/destroy', [ActivityController::class, 'destroy'])->name('activity.destroy');
        Route::get('/activity/{id}/confirm', [ActivityController::class, 'confirmDestroy'])->name('activity.destroy.confirm');
        Route::get('/activity/{id}/send_report', [ActivityController::class, 'sendReport'])->name('activity.send_report');
        Route::post('/activity/index/filter', [ActivityController::class, 'filterPost'])->name('activity.filter');
        Route::get('/activity/filter/{period}/{costumer}/{state}/{date}', [ActivityController::class, 'filter'])->name('activity.filter.get');
    });
});

Route::get('/ajax/orders', [AjaxController::class, 'orders']);
//Route::get('/ajax/active_costumer', [AjaxController::class, 'activeCostumer']);
Route::get('/ajax/costumer', [AjaxController::class, 'costumer']);

