<?php

namespace App\Http\Controllers;

use App\Models\AttivitaInfo;
use Illuminate\Support\Facades\Log;

class TrialController extends Controller
{
    public function trialFunction() {
        Log::debug('trialFunction');
        $activities = AttivitaInfo::where('persona_id', '1')->get();
        foreach ($activities as $activity) {
            Log::debug($activity->descrizione_attivita);
        }
    }
}
