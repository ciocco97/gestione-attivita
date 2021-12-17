<?php

namespace App\Http\Middleware;

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

        if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
            return Redirect::to(route('user.login'));
        }

        return $next($request);
    }
}
