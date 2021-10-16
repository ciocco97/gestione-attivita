<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Http\Utils;
use App\Models\Persona;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon as super_time_parser;

class ActivityController extends Controller
{

    const ACTIVITY_METHODS = array(
        'SHOW' => 0,
        'EDIT' => 1,
        'DELETE' => 2,
        'ADD' => 3
    );
    const PAGES = array(
        'TECHNICIAN' => 0,
        'MANAGER' => 1,
        'ADMINISTRATIVE' => 2,
        'COMMERCIAL' => 3,
        'ADMINISTRATOR' => 4
    );


    private function indexActivityViewForManager_and_Tech($activities)
    {
        $_SESSION['previous_url'] = url()->current();

        Log::debug('indexActivityView');
        $username = $_SESSION['username'];

        $dl = new DataLayer();

        $orders = $dl->listActiveOrder();
        $states = $dl->listActivityState();

        if ($_SESSION['current_page'] == self::PAGES['MANAGER']) {
            $team = $dl->listTeam($_SESSION['user_id']);
            $costumers = $dl->listActiveCostumer();
            $billing_states = $dl->listBillingStates();
            $this->addBillableDuration($activities);
        } else {
            $costumers = $dl->listActiveCostumerForCurrentUser();
            $team = null;
            $billing_states = null;
        }

        $this->changeActivityDateFormat($activities);
        $this->changeActivityDescriptionLenght($activities);

        return view('activity.technician')
            ->with('activities', $activities)
            ->with('username', $username)
            ->with('costumers', $costumers)
            ->with('orders', $orders)
            ->with('states', $states)
            ->with('billing_states', $billing_states)
            ->with('team', $team);
    }

    private function indexActivityViewForAdministrative($activities)
    {
        $_SESSION['previous_url'] = url()->current();

        $dl = new DataLayer();

        $username = $_SESSION['username'];
        $costumers = $dl->listActiveCostumer();
        $orders = $dl->listActiveOrder();
        $users = $dl->listUsers();
        $billing_states = $dl->listBillingStates();

        $this->changeActivityDateFormat($activities);
        $this->changeActivityDescriptionLenght($activities);

        return view('activity.administrative')
            ->with('username', $username)
            ->with('activities', $activities)
            ->with('costumers', $costumers)
            ->with('orders', $orders)
            ->with('billing_states', $billing_states)
            ->with('users', $users);
    }

    public function index()
    {
        Log::debug('Home');
        $_SESSION['current_page'] = self::PAGES['TECHNICIAN'];

        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listActiveActivityByUserID($_SESSION['user_id'], $start_date);

        return $this->indexActivityViewForManager_and_Tech($activities);

    }

    public function managerIndex()
    {
        Log::debug('Manager index');
        $_SESSION['current_page'] = self::PAGES['MANAGER'];

        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listActiveActivityForManagerTableByUserID($_SESSION['user_id'], $start_date);

        return $this->indexActivityViewForManager_and_Tech($activities);
    }

    public function administrativeIndex()
    {
        Log::debug('Administrative index');
        $_SESSION['current_page'] = self::PAGES['ADMINISTRATIVE'];

        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listAdministrativeActivities($start_date);

        return $this->indexActivityViewForAdministrative($activities);
    }

    private function getActivities($user_id, $page, $filter)
    {
        $dl = new DataLayer();
        $roles = $dl->listUserRoles($user_id);

        $activities = null;
        switch ($page) {
            case self::PAGES['TECHNICIAN']:
                $activities = $dl->listActivityTechnician($user_id, $filter);
                break;
            case self::PAGES['MANAGER']:
                break;
            case self::PAGES['ADMINISTRATIVE']:
                break;
        }
        return $activities;
    }

    public function filterPost(Request $request)
    {
        $period = $request->get('period');
        $costumer = $request->get('costumer');
        $state = $request->get('state');
        $date = $request->get('date');
        $team_member_id = $request->get('user');
        $billing_state = $request->get('billing-state');

        $period = $period == null ? -1 : $period;
        $costumer = $costumer == null ? -1 : $costumer;
        $state = $state == null ? -1 : $state;
        $date = $date == null ? -1 : $date;
        $team_member_id = $team_member_id == null ? -1 : $team_member_id;
        $billing_state = $billing_state == null ? -1 : $billing_state;

        Log::debug('filterPost', [
            'period' => $period,
            'costumer' => $costumer,
            'state' => $state,
            'date' => $date,
            'user' => $team_member_id,
            'billed' => $billing_state]);
        return redirect()->route('activity.filter.get',
            ['period' => $period, 'costumer' => $costumer, 'state' => $state, 'date' => $date, 'user' => $team_member_id, 'billing_state' => $billing_state]);
    }

    public function filter($period, $costumer, $state, $date, $team_member_id, $billing_state)
    {
        $user_id = $_SESSION['user_id'];

        $period = $period == -1 ? null : $period;
        $costumer = $costumer == -1 ? null : $costumer;
        $state = $state == -1 ? null : $state;
        $date = $date == -1 ? null : $date;
        $team_member_id = $team_member_id == -1 ? null : $team_member_id;
        $billing_state = $billing_state == -1 ? null : $billing_state;

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
            'date' => $date,
            'user' => $team_member_id,
            'billing_state' => $billing_state
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
        if ($_SESSION['current_page'] == self::PAGES['ADMINISTRATIVE']) {
            Log::debug('Administrative filter');
            $activities = $dl->filterAdministrativeActivities(
                $start_date, $end_date, $costumer, $state, $date, $team_member_id, $billing_state);

            return $this->indexActivityViewForAdministrative($activities);

        } else {
            $activities = $dl->filterActiveActivityByUserID(
                $user_id, $start_date, $end_date, $costumer, $state, $date, $team_member_ids);

            return $this->indexActivityViewForManager_and_Tech($activities);
        }
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

        if ($method == self::ACTIVITY_METHODS['ADD'] || $method == self::ACTIVITY_METHODS['EDIT']) {
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
            ->with('SHOW', self::ACTIVITY_METHODS['SHOW'])
            ->with('EDIT', self::ACTIVITY_METHODS['EDIT'])
            ->with('DELETE', self::ACTIVITY_METHODS['DELETE'])
            ->with('ADD', self::ACTIVITY_METHODS['ADD'])
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
        return $this->sedActivityView(self::ACTIVITY_METHODS['SHOW'], $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::debug('Create_activity');
        return $this->sedActivityView(self::ACTIVITY_METHODS['ADD']);
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
        return $this->sedActivityView(self::ACTIVITY_METHODS['EDIT'], $id);
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
        return $this->sedActivityView(self::ACTIVITY_METHODS['DELETE'], $id);
    }


    private function changeActivityDateFormat($activities)
    {
        foreach ($activities as $activity) {
            $activity->data = super_time_parser::parse($activity->data)->format('d-m-Y');
        }
    }

    private function changeActivityDescriptionLenght($activities)
    {
        foreach ($activities as $activity) {
            $activity->desc_attivita = strlen($activity->descrizione_attivita) >= 30 ?
                substr($activity->descrizione_attivita, 0, 15) . "..." : $activity->descrizione_attivita;
        }
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

}
