<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthController;
use App\Models\Persona;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!isset($_SESSION['logged']) || !$_SESSION['logged'] || !Persona::isActive($_SESSION['user_id'])) {
            return Redirect::to(route('user.logout'));
        }

        return $next($request);
    }
}
