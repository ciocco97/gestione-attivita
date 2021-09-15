<?php

namespace App\Http\Controllers;

use App\DataLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    public function orders(Request $request)
    {
        $costumer_id = $request->input('costumer_id');
        Log::debug('Ajax_orders', ['costumer_id' => $costumer_id]);
        $dl = new DataLayer();
        $orders = $dl->listActiveOrderByCostumerID($costumer_id)->toArray();
        return response()->json($orders);
    }

//    public function activeCostumer(Request $request) {
//        $costumer_id = $request->input('costumer_id');
//        Log::debug('Ajax_active_costumer', ['id' => $costumer_id]);
//        $dl = new DataLayer();
//        $active = $dl->isCostumerActive($costumer_id);
//        return response()->json($active);
//    }

    public function costumer(Request $request)
    {
        $order_id = $request->input('order_id');
        Log::debug('Ajax_costumer', ['order_id' => $order_id]);
        $dl = new DataLayer();
        $costumer = $dl->getCostumerByOrderID($order_id)->toArray();
        return response()->json($costumer);
    }

}
