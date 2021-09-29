<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Mail\ActivityReport;
use App\Models\Attivita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivityMailController extends Controller
{

    public function ajaxSendActivityReport(Request $request)
    {
        $activity_id = $request->input('activity_id');
        $user_id = $_SESSION['user_id'];
        Log::debug('ajaxSendActivityReport', ['activity_id' => $activity_id]);

        $dl = new DataLayer();
        $activity = $dl->getActivityForActivityReport($activity_id, $user_id);

        Mail::to($activity->email)
            ->queue(new ActivityReport($activity));

        $dl->updateActivityReport($activity_id, $user_id, true);

    }

}
