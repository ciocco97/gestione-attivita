<?php

namespace App\Http\Controllers;

use App\DataLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    // Dato un cliente, ritorna i relativi ordini aperti
    public function orders(Request $request)
    {
        $costumer_id = $request->input('costumer_id');
        Log::debug('Ajax_orders', ['costumer_id' => $costumer_id]);
        $dl = new DataLayer();
        $orders = $dl->listActiveOrderByCostumerID($costumer_id)->toArray();
        return response()->json($orders);
    }

    // Dato un ordine, ritorna il relativo cliente
    public function costumer(Request $request)
    {
        $order_id = $request->input('order_id');
        Log::debug('Ajax_costumer', ['order_id' => $order_id]);
        $dl = new DataLayer();
        $costumer = $dl->getCostumerByOrderID($order_id)->toArray();
        return response()->json($costumer);
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

    public function massChangeActivities(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $ids = $request->input('ids');
        $state = $request->input('state');
        Log::debug('massChange', ['ids' => $ids, 'state' => $state]);
        $dl = new DataLayer();
        $dl->stateUpdateByActivityIDS($user_id, $ids, $state);
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

    public function ajaxMassChangeActivityBillingState(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $ids = $request->input('ids');
        $state = $request->input('state');
        Log::debug('ajaxMassChangeActivityBillingState', ['ids' => $ids, 'state' => $state]);
        $dl = new DataLayer();
        $dl->billingStateUpdateByActivityIDS($user_id, $ids, $state);
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

    public function ajaxOrderReportChange(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $order_id = $request->input('order_id');
        $checked = $request->input('checked');
        Log::debug('ajaxOrderReportChange', ['id' => $order_id, 'checked' => $checked]);
        $dl = new DataLayer();
        $dl->changeOrderReport($user_id, $order_id, $checked);

    }

}
