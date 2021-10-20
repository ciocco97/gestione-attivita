<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    const AJAX_METHODS = [
        'order_change_report_index' => 1,
        'order_change_state_index' => 2,
        'activity_change_billable_duration_index' => 3,
        'activity_change_billing_state_index' => 4,
        'activity_send_report_index' => 5,
        'activities_change_billed_index' => 6,
        'activities_change_state_index' => 7
    ];

    // Dato un cliente, ritorna i relativi ordini aperti
    public function orders(Request $request)
    {
        $costumer_id = $request->input('costumer_id');
        Log::debug('Ajax_orders', ['costumer_id' => $costumer_id]);
        $orders = Commessa::listActiveOrderByCostumerID($costumer_id)->toArray();
        return response()->json($orders);
    }

    // Dato un ordine, ritorna il relativo cliente
    public function costumer(Request $request)
    {
        $order_id = $request->input('order_id');
        Log::debug('Ajax_costumer', ['order_id' => $order_id]);
        $dl = new DataLayer();
        $costumer = Cliente::getCostumerByOrderID($order_id)->toArray();
        return response()->json($costumer);
    }


    public function ajaxActivitiesChange(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $ids = $request->input('activity_ids');
        $value = $request->input('value');
        $ajax_method = $request->input('ajax_method');

        Log::debug('ajaxActivitiesChange', ['ids' => $ids, 'value' => $value, 'ajax_method' => $ajax_method]);

        $result = false;
        switch ($ajax_method) {
            case self::AJAX_METHODS['activities_change_billed_index']:
                $result = Attivita::billedUpdateByActivityIDS($user_id, $ids, $value);
                break;
            case self::AJAX_METHODS['activities_change_state_index']:
                $result = Attivita::stateUpdateByActivityIDS($user_id, $ids, $value);
                break;
        }
        return response()->json($result);

    }

    public function ajaxActivityChange(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $activity_id = $request->input('activity_id');
        $value = $request->input('value');
        $ajax_method = $request->input('ajax_method');
        Log::debug('ajaxActivityChange', ['id' => $activity_id, 'value' => $value, 'ajax_method' => $ajax_method]);

        $result = false;
        switch ($ajax_method) {
            case self::AJAX_METHODS['activity_change_billable_duration_index']:
                $result = Attivita::changeActivityBillableDuration($user_id, $activity_id, $value);
                break;
            case self::AJAX_METHODS['activity_change_billing_state_index']:
                $result = Attivita::changeActivityBillingState($user_id, $activity_id, $value);
                break;
            case self::AJAX_METHODS['activity_send_report_index']:
                $result = Attivita::sendActivityReport($user_id, $activity_id);
                break;
        }
        return response()->json($result);
    }

    public function ajaxOrderChange(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $order_id = $request->input('order_id');
        $value = $request->input('value');
        $ajax_method = $request->input('ajax_method');
        Log::debug('ajaxOrderChange', ['id' => $order_id, 'value' => $value, 'ajax_method' => $ajax_method]);

        $result = false;
        switch ($ajax_method) {
            case self::AJAX_METHODS['order_change_report_index']:
                $result = Commessa::changeOrderReport($user_id, $order_id, $value);
                break;
            case self::AJAX_METHODS['order_change_state_index']:
                $result = Commessa::changeOrderState($user_id, $order_id, $value);
                break;
        }
        return response()->json($result);
    }


    public function ajaxChangeActivityBillingState(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $activity_id = $request->input('activity_id');
        $billing_state = $request->input('billing_state');
        Log::debug('ajaxChangeActivityBillingState', ['id' => $activity_id, 'state' => $billing_state]);
        $dl = new DataLayer();
        $dl->changeActivityBillingState($user_id, $activity_id, $billing_state);
    }

    public function ajaxChangeActivityBillableDuration(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $activity_id = $request->input('activity_id');
        $billable_duration = $request->input('billable_duration');
        Log::debug('ajaxChangeActivityBillingState', ['id' => $activity_id, 'state' => $billable_duration]);
        $dl = new DataLayer();
        $dl->changeActivityBillableDuration($user_id, $activity_id, $billable_duration);

    }

    public function massChangeActivities(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $ids = $request->input('ids');
        $state = $request->input('state');
        Log::debug('massChange', ['ids' => $ids, 'state' => $state]);
        $dl = new DataLayer();
        $dl->stateUpdateByActivityIDS($user_id, $ids, $state);
    }

    public function ajaxMassChangeActivityBillingState(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $ids = $request->input('ids');
        $state = $request->input('state');
        Log::debug('ajaxMassChangeActivityBillingState', ['ids' => $ids, 'state' => $state]);
        $dl = new DataLayer();
        $dl->billingStateUpdateByActivityIDS($user_id, $ids, $state);
    }

    // Dato un utente, ritorna se l'utente loggato è manager, amministratore e/o commerciale
    // Se nessun utente è loggato, ritorna {stato: false}
    public function userRoles(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $dl = new DataLayer();
        $roles = $dl->listUserRoles($user_id);
        return response()->json($roles);
    }


}
