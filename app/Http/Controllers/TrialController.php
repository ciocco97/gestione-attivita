<?php

namespace App\Http\Controllers;

use App\Models\AttivitaInfo;
use Illuminate\Support\Facades\Log;

class TrialController extends Controller
{
    public function trialFunction() {
        return view('trial');
    }
}
