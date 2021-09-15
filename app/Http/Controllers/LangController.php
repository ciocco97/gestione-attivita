<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class LangController extends Controller
{
    public function change($lang) {
        Log::debug('LangController::change', ['route_back' => Redirect::back()]);
        session_start();
        $_SESSION['lang'] = $lang;
        return back()->withInput();
    }
}
