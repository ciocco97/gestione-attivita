<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Http\Utils;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon as super_time_parser;

class ActivityController extends Controller
{

    const SHOW = 0;
    const EDIT = 1;
    const DELETE = 2;
    const ADD = 3;

    private function indexActivityView($activities, $manager = false)
    {
        $_SESSION['previous_url'] = url()->current();

        Log::debug('indexActivityView');
        $username = $_SESSION['username'];

        $dl = new DataLayer();

        $orders = $dl->listActiveOrder();
        $states = $dl->listActivityState();
        if ($manager) {
            $team = $dl->listTeam($_SESSION['user_id']);
            $costumers = $dl->listActiveCostumer();
            $billing_states = $dl->listBillingStates();
            $this->addBillableDuration($activities);
        } else {
            $costumers = $dl->listActiveCostumerByUserID();
            $team = null;
            $billing_states = null;
        }

        $this->changeActivityDateFormat($activities);

        return view('activity.technician')
            ->with('activities', $activities)
            ->with('username', $username)
            ->with('costumers', $costumers)
            ->with('orders', $orders)
            ->with('states', $states)
            ->with('billing_states', $billing_states)
            ->with('team', $team);
    }

    private function changeActivityDateFormat($activities)
    {
        foreach ($activities as $activity) {
            $activity->data = super_time_parser::parse($activity->data)->format('d-m-Y');
        }
    }

    public function index()
    {
        Log::debug('Home');
        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listActiveActivityForActivityTableByUserID($_SESSION['user_id'], $start_date);

        return $this->indexActivityView($activities);

    }

    private function addBillableDuration($activities)
    {
        foreach ($activities as $activity) {
            $billable_duration = $activity->durata_fatturabile;
            if ($billable_duration == null) {
                $billable_duration = super_time_parser::parse($activity->durata);
                $billable_duration->minute = $billable_duration->minute + 15 - $billable_duration->minute % 15;
                $activity->durata_fatturabile = $billable_duration->format("H:i");
            }
        }
    }

    public function managerIndex()
    {
        Log::debug('Manager index');
        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listActiveActivityForManagerTableByUserID($_SESSION['user_id'], $start_date);

        return $this->indexActivityView($activities, true);
    }

    public function filterPost(Request $request)
    {
        $period = $request->get('period');
        $period = $period == null ? -1 : $period;
        $costumer = $request->get('costumer');
        $state = $request->get('state');
        $date = $request->get('date');
        $team_member_id = $request->get('user');
        $costumer = $costumer == null ? -1 : $costumer;
        $state = $state == null ? -1 : $state;
        $date = $date == null ? -1 : $date;
        $team_member_id = $team_member_id == null ? -1 : $team_member_id;

        Log::debug('filterPost', [
            'period' => $period,
            'costumer' => $costumer,
            'state' => $state,
            'date' => $date,
            'user' => $team_member_id]);
        return redirect()->route('activity.filter.get',
            ['period' => $period, 'costumer' => $costumer, 'state' => $state, 'date' => $date, 'user' => $team_member_id]);
    }

    public function filter($period, $costumer, $state, $date, $team_member_id)
    {
        $user_id = $_SESSION['user_id'];

        $period = $period == -1 ? null : $period;
        $costumer = $costumer == -1 ? null : $costumer;
        $state = $state == -1 ? null : $state;
        $date = $date == -1 ? null : $date;
        $team_member_id = $team_member_id == -1 ? null : $team_member_id;

        $end_date = null;
        if ($period == 1) { // current week
            $start_date = super_time_parser::now()->startOf('week')->format('Y-m-d');
        } else if ($period == 2) { // current 2 weeks
            $start_date = super_time_parser::now()->startOf('week')->subDays(7)->format('Y-m-d');
        } else if ($period == 3) { // current month
            $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
        } else if ($period == 4) { // last month
            $end_date = super_time_parser::now()->startOfMonth()->subDay()->format('Y-m-d');
            $start_date = super_time_parser::parse($end_date)->startOfMonth()->format('Y-m-d');
        } else {
            $start_date = null;
        }

        Log::debug('filter', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'costumer' => $costumer,
            'state' => $state,
            'date' => $date
        ]);

        $dl = new DataLayer();
        $team_member_ids = null;
        if ($team_member_id != null) {
            if ($team_member_id == -2) {
                $team_member_ids = $dl->listTeamIDS($user_id);
            } else {
                $team_member_ids = array($team_member_id);
            }
        }
        $activities = $dl->filterActiveActivityForActivityTableByUserID(
            $user_id, $start_date, $end_date, $costumer, $state, $date, $team_member_ids);

        return $this->indexActivityView($activities, $team_member_id != null);
    }

    /**
     * sedActivityView (Show, Edit e Destroy) rende parametrica l'invocazione della vista di modifica delle attività
     * @param int $activity_id
     * @param $method
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function sedActivityView($method, int $activity_id = -1)
    {
        $manager = str_contains($_SESSION['previous_url'], 'manager');

        Log::debug('sedActivityView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $dl = new DataLayer();
        $costumers = $orders = $states = $billing_states = $activity = $order = $costumer = $state = $billing_state = null;
        $tech_name = $username;
        if ($activity_id != -1) {
            $activity = $dl->getActivityByActivityAndUserID($activity_id, $user_id);
            $order = $activity->commessa()->get()->first();
            $costumer = $order->cliente()->get()->first();
            $tech_name = Persona::find($activity->persona_id)->nome;
            $billing_state = $activity->statoFatturazione()->get()->first();

            $state = $activity->statoAttivita()->get()->first();
        }

        if ($method == self::ADD || $method == self::EDIT) {
            $costumers = $dl->listActiveCostumer();
            $orders = $dl->listActiveOrder();
            if ($manager) {
                $states = $dl->listActivityState();
                $billing_states = $dl->listManagerBillingStates();
            } else {
                $states = $dl->listActivityStateForTech();
            }
        }

        return view('activity.show')
            ->with('username', $username)
            ->with('method', $method)
            ->with('tech_name', $tech_name)
            ->with('SHOW', self::SHOW)->with('EDIT', self::EDIT)->with('DELETE', self::DELETE)->with('ADD', self::ADD)
            ->with('activity', $activity)->with('current_order', $order)->with('current_costumer', $costumer)->with('current_state', $state)->with('current_billing_state', $billing_state)
            ->with('costumers', $costumers)->with('orders', $orders)->with('states', $states)->with('billing_states', $billing_states)
            ->with('previous_url', $_SESSION['previous_url'])
            ->with('manager', $manager);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        Log::debug('Show_activity', ['id' => $id]);
        return $this->sedActivityView(self::SHOW, $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::debug('Create_activity');
        return $this->sedActivityView(self::ADD);
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
        $order = $request->get('order');
        $date = super_time_parser::parse($request->get('date'))->format('Y-m-d');
        $startTime = $request->get('startTime');
        $endTime = $request->get('endTime');
        $duration = $request->get('duration');
        $location = $request->get('location');
        $description = $request->get('description');
        Log::debug('Store_activity', ['description' => $description]);
        $internalNotes = $request->get('internalNotes');
        $state = $request->get('state');

        $dl = new DataLayer();
        $dl->storeActivity($user_id, $order, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state);

        return Redirect::to($_SESSION['previous_url']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::debug('Edit_activity', ['id' => $id]);
        return $this->sedActivityView(self::EDIT, $id);
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
        Log::debug('Update_activity', ['id' => $id]);
        $user_id = $_SESSION['user_id'];
        $activity_id = $id;
        $order = $request->get('order');
        $date = super_time_parser::parse($request->get('date'))->format('Y-m-d');
        $startTime = $request->get('startTime');
        $endTime = $request->get('endTime');
        $duration = $request->get('duration');
        $location = $request->get('location');
        $description = $request->get('description');
        $internalNotes = $request->get('internalNotes');
        $state = $request->get('state');

        $dl = new DataLayer();
        $dl->updateActivity($user_id, $activity_id, $order, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state);

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
        Log::debug('Destroy_activity', ['id' => $id]);
        $dl = new DataLayer();
        $dl->destroyActivity($id, $_SESSION['user_id']);

        return Redirect::to($_SESSION['previous_url']);
    }

    public function confirmDestroy($id)
    {
        Log::debug('ConfirmDestroy_activity', ['id' => $id]);
        return $this->sedActivityView(self::DELETE, $id);
    }

}
