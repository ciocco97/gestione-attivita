<?php

use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\ActivityController;
use \App\Http\Middleware\MyAuth;
use \App\Http\Middleware\Language;
use \App\Http\Controllers\AjaxController;
use \App\Http\Controllers\ActivityMailController;
use \App\Http\Controllers\CostumerController;

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
    Route::match(['get', 'post'], '/user/reset/password/', [AuthController::class, 'resetPasswordProcedure'])->name('user.reset.password');


    Route::middleware([MyAuth::class])->group(function () {
        Route::get('/user/choose/password', [AuthController::class, 'choosePassword'])->name('user.choose.password');
        Route::post('/user/change/password', [AuthController::class, 'changePassword'])->name('user.change.password');
        Route::resource('activity', ActivityController::class);
        Route::post('/activity/{id}/update', [ActivityController::class, 'update'])->name('activity.update');
        Route::get('/activity/{id}/destroy', [ActivityController::class, 'destroy'])->name('activity.destroy');
        Route::get('/activity/{id}/confirm', [ActivityController::class, 'confirmDestroy'])->name('activity.destroy.confirm');
        Route::post('/activity/index/filter', [ActivityController::class, 'filterPost'])->name('activity.filter');
        Route::get('/activity/filter/{period}/{costumer}/{state}/{date}/{user}/{billing_state}', [ActivityController::class, 'filter'])->name('activity.filter.get');
        Route::get('/ajax/user/roles', [AjaxController::class, 'userRoles']);
        Route::get('/ajax/activity/mass/change', [AjaxController::class, 'massChangeActivities']);
        Route::get('/ajax/activity/send/report', [ActivityMailController::class, 'ajaxSendActivityReport']);
        Route::get('/ajax/activity/change/billing_state', [AjaxController::class, 'ajaxChangeActivityBillingState']);
        Route::get('/ajax/activity/change/change_billable_duration', [AjaxController::class, 'ajaxChangeActivityBillableDuration']);
        Route::get('/ajax/activity/mass/billing_state/change', [AjaxController::class, 'ajaxMassChangeActivityBillingState']);

        Route::get('/activity/manager/index', [ActivityController::class, 'managerIndex'])->name('manager.index');
        Route::get('/activity/administrative/index', [ActivityController::class, 'administrativeIndex'])->name('administrative.index');
        Route::resource('costumer', CostumerController::class);
    });
});

Route::get('/ajax/orders', [AjaxController::class, 'orders']);
//Route::get('/ajax/active_costumer', [AjaxController::class, 'activeCostumer']);
Route::get('/ajax/costumer', [AjaxController::class, 'costumer']);


