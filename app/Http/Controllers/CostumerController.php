<?php

namespace App\Http\Controllers;

use App\DataLayer;
use Carbon\Carbon as super_time_parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CostumerController extends Controller
{

    public function indexCostumerView($start_date)
    {
        $_SESSION['previous_url'] = url()->current();
        $username = $_SESSION['username'];

        $dl = new DataLayer();
        $costumers = $dl->listActiveCostumer();
        list($activities, $not_bill_a_nums) = $dl->listApprovedActivity($start_date);

        $infos = array();
        foreach ($costumers as $costumer) {
            $costumer_id = $costumer->id;
            $not_bill_a_num = $not_bill_a_nums->filter(function ($value) use (&$costumer_id) {
                return $value->cliente_id == $costumer_id;
            })->pluck('attivita_num')->first();
            $costumer_activities = $activities->filter(function ($value) use (&$costumer_id) {
                return $value->cliente_id == $costumer_id;
            })->map(function ($item) {
                $billable_duration = $item->durata_fatturabile;
                if ($billable_duration == null) {
                    $billable_duration = super_time_parser::parse($item->durata);
                    $billable_duration->minute = $billable_duration->minute + 15 - $billable_duration->minute % 15;
                    $item->durata_fatturabile = $billable_duration->format("H:i");
                }
                return $item;
            })->toArray();
            if (count($costumer_activities) != 0) {
                $info = array($costumer, $not_bill_a_num, $costumer_activities);
                array_push($infos, $info);
            }
        }
        $billing_states = $dl->listBillingStates();

        return view('costumer.administrative')
            ->with('username', $username)
            ->with('billing_states', $billing_states)
            ->with('costumers_nums_activities', $infos);
    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        Log::debug('Home');
        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        return $this->indexCostumerView($start_date);
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
