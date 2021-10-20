<?php

namespace App\Http\Controllers;

use App\Http\Utils;
use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoAttivita;
use App\Models\StatoFatturazione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon as super_time_parser;

class ActivityController extends Controller
{

    const METHODS = array(
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


    private function indexActivityView($activities)
    {
        $_SESSION['previous_url'] = url()->current();
        $current_page = $_SESSION['current_page'];
        Log::debug('indexActivityView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $costumers = null;
        $states = null;
        $users = null;
        $billing_states = null;

        switch ($current_page) {
            case self::PAGES['TECHNICIAN']:
                $costumers = Cliente::listActiveCostumerForCurrentUser();
                $states = StatoAttivita::listActivityState();
                break;
            case self::PAGES['MANAGER']:
                $costumers = Cliente::listActiveCostumer();
                $states = StatoAttivita::listActivityState();
                $users = Persona::listTeam($user_id);
                $billing_states = StatoFatturazione::listBillingStates();
                $this->addBillableDuration($activities);
                break;
            case self::PAGES['ADMINISTRATIVE']:
                $costumers = Cliente::listCostumer();
                $users = Persona::listUsers();
                $billing_states = StatoFatturazione::listBillingStates();
                $this->addBillableDuration($activities);
                break;
        }

        $this->changeActivityDateFormat($activities);
        $this->changeActivityDescriptionLenght($activities);

        $user_roles = Persona::listUserRoles($user_id)->toArray();
        return view('activity.technician')
            ->with('activities', $activities)
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('costumers', $costumers)
            ->with('states', $states)
            ->with('billing_states', $billing_states)
            ->with('users', $users)
            ->with('current_page', $_SESSION['current_page'])
            ->with('pages', self::PAGES);
    }

    public function index()
    {
        Log::debug('Home');

        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');
        $activities = Attivita::listActiveActivityByUserID($_SESSION['user_id'], $start_date);

        $_SESSION['current_page'] = self::PAGES['TECHNICIAN'];
        return $this->indexActivityView($activities);

    }

    public function managerIndex()
    {
        Log::debug('Manager index');

        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');
        $activities = Attivita::listActiveActivityForManagerTableByUserID($_SESSION['user_id'], $start_date);

        $_SESSION['current_page'] = self::PAGES['MANAGER'];
        return $this->indexActivityView($activities);
    }

    public function administrativeIndex()
    {
        Log::debug('Administrative index');

        $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
        $activities = Attivita::listAdministrativeActivities($start_date);

        $_SESSION['current_page'] = self::PAGES['ADMINISTRATIVE'];
        return $this->indexActivityView($activities);
    }

    public function filterPost(Request $request)
    {
        $period = $request->get('period');
        $costumer = $request->get('costumer');
        $state = $request->get('state');
        $date = $request->get('date');
        $team_member_id = $request->get('user');
        $accounted_selector = $request->get('billing-state');

        $period = $period == null ? -1 : $period;
        $costumer = $costumer == null ? -1 : $costumer;
        $state = $state == null ? -1 : $state;
        $date = $date == null ? -1 : $date;
        $team_member_id = $team_member_id == null ? -1 : $team_member_id;
        $accounted_selector = $accounted_selector == null ? -1 : $accounted_selector;

        Log::debug('filterPost', [
            'period' => $period,
            'costumer' => $costumer,
            'state' => $state,
            'date' => $date,
            'user' => $team_member_id,
            'accounted' => $accounted_selector]);
        return redirect()->route('activity.filter.get',
            ['period' => $period, 'costumer' => $costumer, 'state' => $state, 'date' => $date, 'user' => $team_member_id, 'billing_state' => $accounted_selector]);
    }

    public function filter($period, $costumer, $state, $date, $team_member_id, $accounted_selector)
    {
        $user_id = $_SESSION['user_id'];

        $period = $period == -1 ? null : $period;
        $costumer = $costumer == -1 ? null : $costumer;
        $state = $state == -1 ? null : $state;
        $date = $date == -1 ? null : $date;
        $team_member_id = $team_member_id == -1 ? null : $team_member_id;
        $accounted_selector = $accounted_selector == -1 ? null : $accounted_selector;

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
            'billing_state' => $accounted_selector
        ]);

        $team_member_ids = null;
        if ($team_member_id != null) {
            if ($team_member_id == -2) {
                $team_member_ids = Persona::listTeamIDS($user_id);
            } else {
                $team_member_ids = array($team_member_id);
            }
        }

        $accounted = null;
        if ($accounted_selector != null) {
            $accounted = $accounted_selector == 10 ? 2 : $accounted;
            $accounted = $accounted_selector == 11 ? 1 : $accounted;
        }

        if ($_SESSION['current_page'] == self::PAGES['ADMINISTRATIVE']) {
            Log::debug('Administrative filter');
            $activities = Attivita::filterAdministrativeActivities(
                $start_date, $end_date, $costumer, $state, $date, $team_member_id, $accounted_selector, $accounted);

        } else {
            Log::debug('Tech filter');
            $activities = Attivita::filterActiveActivityByUserID(
                $user_id, $start_date, $end_date, $costumer, $state, $date, $team_member_ids);
        }
        return $this->indexActivityView($activities);
    }


    private function sedActivityView($method, int $activity_id = -1)
    {
        $manager = str_contains($_SESSION['previous_url'], 'manager');

        Log::debug('sedActivityView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $costumers = $orders = $states = $billing_states = $activity = $order = $costumer = $state = $billing_state = null;
        $tech_name = $username;
        if ($activity_id != -1) {
            $activity = Attivita::getActivityByActivityAndUserID($activity_id, $user_id);
            $order = $activity->commessa()->get()->first();
            $costumer = $order->cliente()->get()->first();
            $tech_name = Persona::find($activity->persona_id)->nome;
            $billing_state = $activity->statoFatturazione()->get()->first();

            $state = $activity->statoAttivita()->get()->first();
        }

        if ($method == self::METHODS['ADD'] || $method == self::METHODS['EDIT']) {
            $costumers = Cliente::listActiveCostumer();
            $orders = Commessa::listActiveOrder();
            if ($manager) {
                $states = StatoAttivita::listActivityState();
                $billing_states = StatoFatturazione::listBillingStates();
            } else {
                $states = StatoAttivita::listActivityStateForTech();
            }
        }

        $user_roles = Persona::listUserRoles($user_id)->toArray();
        return view('activity.show')
            ->with('username', $username)
            ->with('user_roles', $user_roles)
            ->with('method', $method)
            ->with('tech_name', $tech_name)
            ->with('SHOW', self::METHODS['SHOW'])
            ->with('EDIT', self::METHODS['EDIT'])
            ->with('DELETE', self::METHODS['DELETE'])
            ->with('ADD', self::METHODS['ADD'])
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
        return $this->sedActivityView(self::METHODS['SHOW'], $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::debug('Create_activity');
        return $this->sedActivityView(self::METHODS['ADD']);
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

        Attivita::storeActivity($user_id, $order, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state);

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
        return $this->sedActivityView(self::METHODS['EDIT'], $id);
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

        Attivita::updateActivity($user_id, $activity_id, $order, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state);

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
        Attivita::destroyActivity($id, $_SESSION['user_id']);

        return Redirect::to($_SESSION['previous_url']);
    }

    public function confirmDestroy($id)
    {
        Log::debug('ConfirmDestroy_activity', ['id' => $id]);
        return $this->sedActivityView(self::METHODS['DELETE'], $id);
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
