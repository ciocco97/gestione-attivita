<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        Log::debug('Language::hanle');
        session_start();
        if(isset($_SESSION['lang'])) {
            App::setLocale($_SESSION['lang']);
        }
        return $next($request);
    }
}
