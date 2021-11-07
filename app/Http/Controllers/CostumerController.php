<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoCommessa;
use Carbon\Carbon as super_time_parser;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CostumerController extends Controller
{

    public function indexCostumerViewOld($activities)
    {
        $_SESSION['previous_url'] = url()->current();
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $dl = new DataLayer();
        $costumers = $dl->listActiveCostumer();
        list($activities, $not_bill_a_nums) = $dl->getCommercialInfos($start_date);

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

        $user_roles = $dl->listUserRoles($user_id)->toArray();
        return view('costumer.commercial')
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('billing_states', $billing_states)
            ->with('costumers_nums_activities', $infos);
    }

    public function indexCostumerView($costumers_infos)
    {
        $_SESSION['previous_url'] = url()->current();
        Log::debug('indexCostumerView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $order_states = StatoCommessa::listOrderStates();
        $user_roles = Persona::listUserRoles($user_id)->toArray();
        return view('costumer.commercial')
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('order_states', $order_states)
            ->with('costumers_orders_nums', $costumers_infos)
            ->with('pages', Shared::PAGES)
            ->with('current_page', $_SESSION['current_page']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        Log::debug('Home');

        $costumers_infos = $this->getCostumersInfos();

        $_SESSION['current_page'] = Shared::PAGES['COMMERCIAL'];
        return $this->indexCostumerView($costumers_infos);
    }

    private function getCostumersInfos(): array
    {
        $costumers = Cliente::listCostumer();
        $numAccountedActivities_perCostumer = Cliente::getNumActivitiesPerCostumer(true);
        $numActivities_perCostumer = Cliente::getNumActivitiesPerCostumer();

        $orders = Commessa::listOrderInfos();

        $costumers_infos = array();
        foreach ($costumers as $costumer) {
            $costumer_id = $costumer->id;
            $costumer_orders = $orders->filter(function ($value) use (&$costumer_id) {
                return $value->cliente_id == $costumer_id;
            });
            $costumer_accounted_activities_num = $numAccountedActivities_perCostumer->filter(function ($value) use (&$costumer_id) {
                return $value->cliente_id == $costumer_id;
            })->pluck('attivita_num')->first();

            $costumer_activities_num = $numActivities_perCostumer->filter(function ($value) use (&$costumer_id) {
                return $value->cliente_id == $costumer_id;
            })->pluck('attivita_num')->first();

            if ($costumer_activities_num == null) {
                $costumer_activities_num = 0;
            }
            if ($costumer_accounted_activities_num == null) {
                $costumer_accounted_activities_num = 0;
            }

            $info = array($costumer, $costumer_orders, $costumer_activities_num, $costumer_accounted_activities_num);
            array_push($costumers_infos, $info);
        }

//        dump($costumers_infos);
//        exit();

        return $costumers_infos;

    }

    public function sedCostumerView($method, int $costumer_id = -1)
    {
        Log::debug('sedCostumerView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $costumer = null;
        if ($costumer_id != -1) {
            $costumer = Cliente::getCostumerByID($costumer_id);
        }

        $user_roles = Persona::listUserRoles($user_id)->toArray();
        return view('costumer.show_costumer')
            ->with('method', $method)
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('costumer', $costumer)
            ->with('SHOW', Shared::METHODS['SHOW'])
            ->with('EDIT', Shared::METHODS['EDIT'])
            ->with('DELETE', Shared::METHODS['DELETE'])
            ->with('ADD', Shared::METHODS['ADD'])
            ->with('previous_url', $_SESSION['previous_url']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->sedCostumerView(Shared::METHODS['ADD']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $_SESSION['user_id'];
        $name = $request->get('name');
        $email = $request->get('email');
        $report = $request->get('report') == "on" ? 1 : 0;
        Log::debug('Store_costumer', [
            'name' => $name,
            'email' => $email,
            'report' => $report
        ]);
        Cliente::storeCostumer($user_id, $name, $email, $report);

        return Redirect::to($_SESSION['previous_url']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->sedCostumerView(Shared::METHODS['SHOW'], $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->sedCostumerView(Shared::METHODS['EDIT'], $id);
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
        $user_id = $_SESSION['user_id'];
        $name = $request->get('name');
        $email = $request->get('email');
        $report = $request->get('report') == "on" ? 1 : 0;
        Log::debug('Update_costumer', ['id' => $id]);

        Cliente::updateCostumer($user_id, $id, $name, $email, $report);

        return Redirect::to($_SESSION['previous_url']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::debug('Destroy_costumer', ['id' => $id]);
        Cliente::destroyCostumer($id, $_SESSION['user_id']);

        return Redirect::to($_SESSION['previous_url']);
    }

    public function confirmDestroy($id)
    {
        Log::debug('ConfirmDestroy_costumer', ['id' => $id]);
        return $this->sedCostumerView(Shared::METHODS['DELETE'], $id);
    }
}
