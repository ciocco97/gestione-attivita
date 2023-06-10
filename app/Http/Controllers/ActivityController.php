<?php

namespace App\Http\Controllers;

use App\Exports\ActivitiesExport;
use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoAttivita;
use App\Models\StatoFatturazione;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon as super_time_parser;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{


    private function indexActivityView($activities)
    {
        $_SESSION['previous_url'] = url()->current();
        $current_page = $_SESSION['current_page'];
        Log::debug('indexActivityView');
        $user_id = $_SESSION['user_id'];

        $costumers = null;
        $orders = null;
        $states = null;
        $users = null;
        $billing_states = null;

        switch ($current_page) {
            case Shared::PAGES['TECHNICIAN']:
                $costumers = Cliente::listActiveCostumerForCurrentUser();
                $orders = Commessa::listActiveOrder();
                $states = StatoAttivita::listActivityState();
                break;
            case Shared::PAGES['MANAGER']:
                $costumers = Cliente::listActiveCostumer();
                $orders = [];
                $states = StatoAttivita::listActivityState();
                $users = Persona::listTeam($user_id);
                $billing_states = StatoFatturazione::listBillingStates();
                $this->addBillableDurationToActivities($activities);
                break;
            case Shared::PAGES['ADMINISTRATIVE']:
                $costumers = Cliente::listCostumer();
                $orders = [];
                $users = Persona::listUsers();
                $billing_states = StatoFatturazione::listBillingStates();
                $this->addBillableDurationToActivities($activities);
                $_SESSION['last_viewed_activities_ids'] = $activities->pluck('id');
                break;
        }

        $this->changeActivityDateFormat($activities);
        $this->changeActivityDescriptionLenght($activities);

        return view('activity.technician')
            ->with('activities', $activities)
            ->with('costumers', $costumers)
            ->with('orders', $orders)
            ->with('states', $states)
            ->with('billing_states', $billing_states)
            ->with('users', $users)
            ->with('current_page', $_SESSION['current_page']);
    }

    public function index()
    {
        Log::debug('Home');

        $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
        $activities = Attivita::listActiveActivityByUserID($_SESSION['user_id'], $start_date);

        $_SESSION['current_page'] = Shared::PAGES['TECHNICIAN'];
        return $this->indexActivityView($activities);

    }

    public function managerIndex()
    {
        Log::debug('Manager index');

        $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
        $activities = Attivita::listActiveActivityForManagerTableByUserID($_SESSION['user_id'], $start_date);

        $_SESSION['current_page'] = Shared::PAGES['MANAGER'];
        return $this->indexActivityView($activities);
    }

    public function administrativeIndex()
    {
        Log::debug('Administrative index');

        $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
        $activities = Attivita::listAdministrativeActivities($start_date);

        $_SESSION['current_page'] = Shared::PAGES['ADMINISTRATIVE'];
        return $this->indexActivityView($activities);
    }

    public function filterPost(Request $request)
    {
        $period = $request->get('period');
        $costumer = $request->get('master_costumer_filter');
        $order = $request->get('master_order_filter');
        $state = $request->get('master_state_filter');
        $date_start = $request->get('master_date_filter1');
        $date_end = $request->get('master_date_filter2');
        $team_member_id = $request->get('master_user_filter');
        $billing_accounted_selector = $request->get('billing-state');

        $period = $period == null ? -1 : $period;
        $costumer = $costumer == null ? -1 : $costumer;
        $order = $order == null ? -1 : $order;
        $state = $state == null ? -1 : $state;
        $date_start = $date_start == null ? -1 : $date_start;
        $date_end = $date_end == null ? -1 : $date_end;
        $team_member_id = $team_member_id == null ? -1 : $team_member_id;
        $billing_accounted_selector = $billing_accounted_selector == null ? -1 : $billing_accounted_selector;

        Log::debug('filterPost_activity', [
            'period' => $period,
            'costumer' => $costumer,
            'order' => $order,
            'state' => $state,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'user' => $team_member_id,
            'accounted' => $billing_accounted_selector]);
        return redirect()->route('activity.filter.get',
            ['period' => $period, 'costumer' => $costumer, 'order' => $order, 'state' => $state, 'date_start' => $date_start, 'date_end' => $date_end, 'user' => $team_member_id, 'billing_accounted_state' => $billing_accounted_selector]);
    }

    public function filter($period, $costumer, $order, $state, $date_start, $date_end, $team_member_id, $billing_accounted_state)
    {
        $user_id = $_SESSION['user_id'];

        $period = $period == -1 ? null : $period;
        $costumer = $costumer == -1 ? null : $costumer;
        $order = $order == -1 ? null : $order;
        $state = $state == -1 ? null : $state;
        $date_start = $date_start == -1 ? null : $date_start;
        $date_end = $date_end == -1 ? null : $date_end;
        $team_member_id = $team_member_id == -1 ? null : $team_member_id;
        $billing_accounted_state = $billing_accounted_state == -1 ? null : $billing_accounted_state;

        $date = null;
        $start_date = null;
        $end_date = null;
        if ($date_start != null) {
            $start_date = $date_start;
            if ($date_end != null) {
                $end_date = $date_end;
            } else {
                $date_end = Carbon::now()->toDateString();
            }
        } else {
            if ($period == Shared::FILTER_PERIOD['CURRENT_WEEK']) { // current week
                $start_date = super_time_parser::now()->startOf('week')->format('Y-m-d');
            } else if ($period == Shared::FILTER_PERIOD['CURRENT_TWO_WEEKS']) { // current 2 weeks
                $start_date = super_time_parser::now()->startOf('week')->subDays(7)->format('Y-m-d');
            } else if ($period == Shared::FILTER_PERIOD['CURRENT_MONTH']) { // current month
                $start_date = super_time_parser::now()->startOfMonth()->format('Y-m-d');
            } else if ($period == Shared::FILTER_PERIOD['LAST_MONTH']) { // last month
                $end_date = super_time_parser::now()->startOfMonth()->subDay()->format('Y-m-d');
                $start_date = super_time_parser::parse($end_date)->startOfMonth()->format('Y-m-d');
            } else {
                $start_date = null;
            }
        }

        Log::debug('filter_activity', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'costumer' => $costumer,
            'order' => $order,
            'state' => $state,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'user' => $team_member_id,
            'billing_accounted_state' => $billing_accounted_state
        ]);

        $team_member_ids = null;
        if ($team_member_id != null) {
            if ($team_member_id == Shared::FILTER_TEAM['TEAM_MEMBER_NOT_SELECTED']) {
                $team_member_ids = Persona::listTeamIDS($user_id);
            } else {
                $team_member_ids = array($team_member_id);
            }
        }

        $accounted = null;
        if ($billing_accounted_state != null) {
            $accounted = $billing_accounted_state == Shared::FILTER_ACCOUNTED['ACCOUNTED'] ? 2 : $accounted;
            $accounted = $billing_accounted_state == Shared::FILTER_ACCOUNTED['NOT_ACCOUNTED'] ? 1 : $accounted;
        }
        if ($_SESSION['current_page'] == Shared::PAGES['ADMINISTRATIVE']) {
            Log::debug('Administrative filter');
            $activities = Attivita::filterAdministrativeActivities(
                $start_date, $end_date, $costumer, $state, $date, $team_member_id, $billing_accounted_state, $accounted);

        } else {
            Log::debug('Tech filter');
            $activities = Attivita::filterActiveActivityByUserID(
                $user_id, $start_date, $end_date, $costumer, $state, $date, $team_member_ids);
        }
        return $this->indexActivityView($activities);
    }

    public function downloadCSV()
    {
        $user_id = $_SESSION['user_id'];

        $result = Attivita::listActivitiesByID($user_id, $_SESSION['last_viewed_activities_ids']);
        foreach ($result as $activity) {
            $billable_duration = $activity->DurataFatturabile;

            if ($billable_duration == null) {
                $billable_duration = super_time_parser::parse($activity->Durata);
                if ($billable_duration->minute % 15 != 0) {
                    $billable_duration->minute = $billable_duration->minute + 15 - $billable_duration->minute % 15;
                }
                $activity->DurataFatturabile = $billable_duration->format("H:i");
            }
            $activity->DurataFatturabile = super_time_parser::parse($activity->DurataFatturabile)->format("H:i");
        }
        $export = new ActivitiesExport($result);

        return Excel::download($export, 'activities.xlsx');
    }


    private function sedActivityView($method, int $activity_id = -1)
    {
        $manager = $_SESSION['current_page'] == Shared::PAGES['MANAGER'];
        Log::debug('sedActivityView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $costumers = $orders = $states = $billing_states = $activity = $order = $costumer = $state = $billing_state = null;
        $tech_name = $username;
        if ($activity_id != -1) {
            $activity = Attivita::getActivityByActivityAndUserID($activity_id, $user_id);
            $this->addBillableDuration($activity);
            $order = $activity->commessa()->get()->first();
            $costumer = $order->cliente()->get()->first();
            $tech_name = Persona::find($activity->persona_id)->nome;
            $billing_state = $activity->statoFatturazione()->get()->first();

            $state = $activity->statoAttivita()->get()->first();
        }

        if ($method == Shared::METHODS['ADD'] || $method == Shared::METHODS['EDIT']) {
            $costumers = Cliente::listActiveCostumer();
            $temp_costumer_id = $costumer == null? null : $costumer->id;
            $orders = Commessa::listActiveOrder($temp_costumer_id);
            if ($manager) {
                $states = StatoAttivita::listActivityState();
                $billing_states = StatoFatturazione::listBillingStates();
            } else {
                $states = StatoAttivita::listActivityStateForTech();
            }
        }


        return view('activity.show')
            ->with('method', $method)
            ->with('tech_name', $tech_name)
            ->with('SHOW', Shared::METHODS['SHOW'])
            ->with('EDIT', Shared::METHODS['EDIT'])
            ->with('DELETE', Shared::METHODS['DELETE'])
            ->with('ADD', Shared::METHODS['ADD'])
            ->with('activity', $activity)->with('current_order', $order)->with('current_costumer', $costumer)->with('current_state', $state)->with('current_billing_state', $billing_state)
            ->with('costumers', $costumers)->with('orders', $orders)->with('states', $states)->with('billing_states', $billing_states)
            ->with('previous_url', $_SESSION['previous_url']);

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
        return $this->sedActivityView(Shared::METHODS['SHOW'], $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::debug('Create_activity');
        return $this->sedActivityView(Shared::METHODS['ADD']);
    }

    public function calculateDuration($duration, $start_time, $end_time): string
    {
        $duration = super_time_parser::parse($duration);

        if ($duration->format('H:i') == "00:00") {
            $start_time = super_time_parser::parse($start_time);
            $end_time = super_time_parser::parse($end_time);
            $duration = $end_time->diff($start_time);
            $duration = $duration->h . ':' . $duration->i;
        }
        return $duration;
    }

    public function ActivityFromPostHTTP(Request $request, $id = null)
    {
        $user_id = $_SESSION['user_id'];
        $activity_order_id = $request->get('order');
        $activity_date = super_time_parser::parse($request->get('date'))->format('Y-m-d');
        $activity_start = $request->get('startTime');
        $activity_end = $request->get('endTime');
        $activity_duration = $request->get('duration');
        $activity_duration = $this->calculateDuration($activity_duration, $activity_start, $activity_end);
        $activity_location = $request->get('location');
        $activity_description = $request->get('description');
        $activity_internal_notes = $request->get('internalNotes');
        $activity_state_id = $request->get('state');
        $activity_billing_state_id = $activity_billable_duration = null;
        if ($_SESSION['current_page'] != Shared::PAGES['TECHNICIAN']) {
            $activity_billing_state_id = $request->get('billing_state');
            $activity_billable_duration = $request->get('billable_duration');
        }

        if ($id == null) {
            Attivita::storeActivity(
                $user_id, $activity_order_id, $activity_date, $activity_start,
                $activity_end, $activity_duration, $activity_location,
                $activity_description, $activity_internal_notes, $activity_state_id
            );
        } else {
            Attivita::updateActivity(
                $user_id, $id, $activity_order_id, $activity_date, $activity_start,
                $activity_end, $activity_duration, $activity_billable_duration, $activity_location,
                $activity_description, $activity_internal_notes, $activity_state_id,
                $activity_billing_state_id
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug('Store_activity', ['description' => $request->get('description')]);
        $this->ActivityFromPostHTTP($request);

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
        return $this->sedActivityView(Shared::METHODS['EDIT'], $id);
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
        $this->ActivityFromPostHTTP($request, $id);

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
        return $this->sedActivityView(Shared::METHODS['DELETE'], $id);
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
                substr($activity->descrizione_attivita, 0, 27) . "..." : $activity->descrizione_attivita;
        }
    }

    private function addBillableDuration($activity)
    {
        $billable_duration = $activity->durata_fatturabile;

        if ($billable_duration == null) {
            $billable_duration = super_time_parser::parse($activity->durata);
            if ($billable_duration->minute % 15 != 0) {
                $billable_duration->minute = $billable_duration->minute + 15 - $billable_duration->minute % 15;
            }
            $activity->durata_fatturabile = $billable_duration->format("H:i");
        }
    }

    private function addBillableDurationToActivities($activities)
    {
        foreach ($activities as $activity) {
            $this->addBillableDuration($activity);
        }
    }

}
