<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\ActivityController;
use \App\Http\Middleware\MyAuth;
use \App\Http\Middleware\Language;
use \App\Http\Controllers\AjaxController;
use \App\Http\Controllers\CostumerController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\UserController;

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
    Route::get('/ajax/user/check_credentials', [AjaxController::class, 'ajaxCheckCredentials']);


    Route::middleware([MyAuth::class])->group(function () {
        Route::get('/user/choose/password', [AuthController::class, 'choosePassword'])->name('user.choose.password');
        Route::post('/user/change/password', [AuthController::class, 'changePassword'])->name('user.change.password');

        Route::get('/image/profile/{id}/show', [ImageController::class, 'showImageProfile'])->name('image.profile.show');

        Route::resource('activity', ActivityController::class);
        Route::post('/activity/{id}/update', [ActivityController::class, 'update'])->name('activity.update');
        Route::get('/activity/{id}/destroy', [ActivityController::class, 'destroy'])->name('activity.destroy');
        Route::get('/activity/{id}/confirm', [ActivityController::class, 'confirmDestroy'])->name('activity.destroy.confirm');
        Route::post('/activity/index/filter', [ActivityController::class, 'filterPost'])->name('activity.filter');
        Route::get('/activity/filter/{period}/{costumer}/{order}/{state}/{date_start}/{date_end}/{user}/{billing_accounted_state}', [ActivityController::class, 'filter'])->name('activity.filter.get');

        Route::get('/export/activity', [ActivityController::class, 'downloadCSV']);

        Route::get('/ajax/shared/vars', [AjaxController::class, 'getSharedVariables']);

        Route::get('/ajax/activity/change', [AjaxController::class, 'ajaxActivityChange']);
        Route::get('/ajax/activities/change', [AjaxController::class, 'ajaxActivitiesChange']);

        Route::get('/ajax/ordersByCostumer', [AjaxController::class, 'ordersByCostumer']);
        Route::get('/ajax/costumerByOrder', [AjaxController::class, 'costumerByOrder']);

        Route::get('/ajax/user/change', [AjaxController::class, 'ajaxUserChange']);


        Route::get('/activity/manager/index', [ActivityController::class, 'managerIndex'])->name('manager.index');
        Route::get('/activity/administrative/index', [ActivityController::class, 'administrativeIndex'])->name('administrative.index');

        Route::resource('costumer', CostumerController::class);
        Route::get('/costumer/{id}/confirm', [CostumerController::class, 'confirmDestroy'])->name('costumer.destroy.confirm');
        Route::post('/costumer/{id}/update', [CostumerController::class, 'update'])->name('costumer.update');
        Route::get('/costumer/{id}/destroy', [CostumerController::class, 'destroy'])->name('costumer.destroy');
        Route::post('/costumer/index/filter', [CostumerController::class, 'filterPost'])->name('costumer.filter');
        Route::get('/costumer/filter/{costumer}/{state}', [CostumerController::class, 'filter'])->name('costumer.filter.get');

        Route::resource('order', OrderController::class);
        Route::get('/order/{id}/confirm', [OrderController::class, 'confirmDestroy'])->name('order.destroy.confirm');
        Route::post('/order/{id}/update', [OrderController::class, 'update'])->name('order.update');
        Route::get('/order/{id}/destroy', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::get('/ajax/order/change', [AjaxController::class, 'ajaxOrderChange']);

        Route::resource('user', UserController::class);
        Route::get('/user/{id}/confirm', [UserController::class, 'destroy'])->name('user.destroy.confirm');
    });
});

//Route::get('/ajax/active_costumer', [AjaxController::class, 'activeCostumer']);


