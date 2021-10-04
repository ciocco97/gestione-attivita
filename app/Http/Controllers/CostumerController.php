<?php

namespace App\Http\Controllers;

use App\DataLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CostumerController extends Controller
{

    public function indexCostumerView($costumers, $orders)
    {
        $_SESSION['previous_url'] = url()->current();
        $username = $_SESSION['username'];
        return view('costumer.administrative')
            ->with('username', $username)
            ->with('costumers', $costumers)
            ->with('orders', $orders);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::debug('Home');

        $dl = new DataLayer();
        $costumers = $dl->listActiveCostumer();
        $activities = $dl->listApprovedActivitiesForAdministrativePage();

        return $this->indexCostumerView($costumers, $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
