<?php

namespace App\Http\Controllers;

use App\DataLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function sedOrderView($method, int $order_id = -1)
    {
        Log::debug('sedOrderView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $dl = new DataLayer();

        $order = null;
        $current_costumer = null;
        $current_state = null;
        if ($order_id != -1) {
            $order = $dl->getOrderByID($order_id);
            $current_costumer = $dl->getCostumerByOrderID($order->id);
            $current_state = $order->statoCommessa()->get()->first();
//            dump($order->descrizione_commessa);
//            exit();
        }

        $costumers = $dl->listCostumer();
        $states = $dl->listOrderStates();

        $user_roles = $dl->listUserRoles($user_id)->toArray();
        return view('costumer.show_order')
            ->with('method', $method)
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('order', $order)
            ->with('current_costumer', $current_costumer)
            ->with('costumers', $costumers)
            ->with('current_state', $current_state)
            ->with('states', $states)
            ->with('SHOW', ActivityController::METHODS['SHOW'])
            ->with('EDIT', ActivityController::METHODS['EDIT'])
            ->with('DELETE', ActivityController::METHODS['DELETE'])
            ->with('ADD', ActivityController::METHODS['ADD'])
            ->with('previous_url', $_SESSION['previous_url']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->sedOrderView(ActivityController::METHODS['ADD']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $description = $request->get('description');
        $costumer = $request->get('costumer');
        $state = $request->get('state');
        $report = $request->get('report') == "on" ? 1 : 0;
        Log::debug('Store_costumer', [
            'description' => $description,
            'costumer' => $costumer,
            'state' => $state,
            'report' => $report
        ]);
        $dl = new DataLayer();
        $dl->storeOrder($user_id, $description, $costumer, $state, $report);

        return Redirect::to($_SESSION['previous_url']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->sedOrderView(ActivityController::METHODS['SHOW'], $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->sedOrderView(ActivityController::METHODS['EDIT'], $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = $_SESSION['user_id'];
        $description = $request->get('description');
        $costumer = $request->get('costumer');
        $state = $request->get('state');
        $report = $request->get('report') == "on" ? 1 : 0;
        Log::debug('Update_costumer', [
            'description' => $description,
            'costumer' => $costumer,
            'state' => $state,
            'report' => $report
        ]);
        $dl = new DataLayer();
        $dl->updateOrder($user_id, $description, $costumer, $state, $report);

        return Redirect::to($_SESSION['previous_url']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::debug('Destroy_order', ['id' => $id]);
        $dl = new DataLayer();
        $dl->destroyCostumer($id, $_SESSION['user_id']);

        return Redirect::to($_SESSION['previous_url']);
    }

    public function confirmDestroy($id)
    {
        Log::debug('ConfirmDestroy_order', ['id' => $id]);
        return $this->sedOrderView(ActivityController::METHODS['DELETE'], $id);
    }
}
