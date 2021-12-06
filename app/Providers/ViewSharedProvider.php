<?php

namespace App\Providers;

use App\Http\Controllers\Shared;
use App\Models\Persona;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ViewSharedProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('PAGES', Shared::PAGES)
                ->with('LANG', App::getLocale());

        });

        view()->composer('navbar.nav', function ($view) {
            if (isset($_SESSION['user_id']) && ($user_id = $_SESSION['user_id']) != -1) {
                $username = $_SESSION['username'];
                $user_roles = Persona::listUserRoles($user_id)->toArray();
                $view->with('username', $username)
                    ->with('user_roles', $user_roles)
                    ->with('ROLES', Shared::ROLES);

            }
        });

        view()->composer(['activity.*'], function ($view) {
            $view->with('ACTIVITY_STATES', Shared::ACTIVITY_STATES)
                ->with('ACTIVITY_ACCOUNTED_STATES', Shared::ACTIVITY_ACCOUNTED_STATES)
                ->with('ACTIVITY_BILLING_STATES', Shared::ACTIVITY_BILLING_STATES)
                ->with('FILTER_ACCOUNTED', Shared::FILTER_ACCOUNTED);
        });

        view()->composer('*.period', function ($view) {
            $view->with('FILTER_PERIOD', Shared::FILTER_PERIOD);
        });

        view()->composer('*.user', function ($view) {
            $view->with('FILTER_TEAM', Shared::FILTER_TEAM);
        });

        view()->composer('*.administrator', function ($view) {
            $view->with('USER_ACTIVE', Shared::USER_ACTIVE)
                ->with('AJAX_METHODS', Shared::AJAX_METHODS)
                ->with('USER_ROLES', Shared::ROLES)
            ;
        });

    }
}
