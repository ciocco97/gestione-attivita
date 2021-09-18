<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Http\Utils;
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

    private function indexActivityView($activities)
    {
        $_SESSION['previous_url'] = url()->current();

        Log::debug('indexActivityView');
        $username = $_SESSION['username'];

        $dl = new DataLayer();

        $costumers = $dl->listActiveCostumerByUserID();
        $orders = $dl->listActiveOrder();
        $states = $dl->listActivityState();


        return view('activity.technician')
            ->with('activities', $activities)
            ->with('username', $username)
            ->with('costumers', $costumers)
            ->with('orders', $orders)
            ->with('states', $states);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::debug('Home');
        $start_date = super_time_parser::now()->subDays(7)->format('Y-m-d');

        $dl = new DataLayer();
        $activities = $dl->listActiveActivityForActivityTableByUserID($_SESSION['user_id'], $start_date);

        foreach ($activities as $activity) {
            $activity->data = super_time_parser::parse($activity->data)->format('d-m-Y');
        }

        return $this->indexActivityView($activities);

    }

    public function filterPost(Request $request) {
        $period = $request->get('period');$period = $period == null?-1:$period;
        $costumer = $request->get('costumer'); $state = $request->get('state'); $date = $request->get('date');
        $costumer = $costumer == null?-1:$costumer; $state = $state == null?-1:$state; $date = $date == null?-1:$date;
        Log::debug('filterPost', [
            'period' => $period,
            'costumer' => $costumer,
            'state' => $state,
            'date' => $date]);
        return redirect()->route('activity.filter.get',
            ['period' => $period, 'costumer' => $costumer, 'state' => $state, 'date' => $date]);
    }

    public function filter($period, $costumer, $state, $date)
    {
        $period = $period == -1?null:$period;
        $costumer = $costumer == -1?null:$costumer;
        $state = $state == -1?null:$state;
        $date = $date == -1?null:$date;

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
        $activities = $dl->filterActiveActivityForActivityTableByUserID(
            $_SESSION['user_id'], $start_date, $end_date, $costumer, $state, $date);

        foreach ($activities as $activity) {
            $activity->data = super_time_parser::parse($activity->data)->format('d-m-Y');
        }

        return $this->indexActivityView($activities);
    }

    /**
     * sedActivityView (Show, Edit e Destroy) rende parametrica l'invocazione della vista di modifica delle attività
     * @param int $activity_id
     * @param $method
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function sedActivityView($method, int $activity_id = -1)
    {
        Log::debug('sedActivityView');
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];

        $dl = new DataLayer();
        $costumers = $orders = $states = $activity = $order = $costumer = $state = null;
        if ($activity_id != -1) {
            $activity = $dl->getActivityByActivityAndUserID($activity_id, $user_id);
            $order = $activity->commessa()->get()->first();
            $costumer = $order->cliente()->get()->first();
            $state = $activity->statoAttivita()->get()->first();
        }

        if ($method == self::ADD || $method == self::EDIT) {
            $costumers = $dl->listActiveCostumer();
            $orders = $dl->listActiveOrder();
            $states = $dl->listActivityStateForTech();
        }

        return view('activity.show')
            ->with('username', $username)
            ->with('method', $method)
            ->with('SHOW', self::SHOW)->with('EDIT', self::EDIT)->with('DELETE', self::DELETE)->with('ADD', self::ADD)
            ->with('activity', $activity)->with('current_order', $order)->with('current_costumer', $costumer)->with('current_state', $state)
            ->with('costumers', $costumers)->with('orders', $orders)->with('states', $states)
            ->with('previous_url', $_SESSION['previous_url']);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

        return Redirect::to($_SESSION['back_link']);
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
        $dl->updateActivity($activity_id, $user_id, $order, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state);

        return Redirect::to($_SESSION['back_link']);
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
        $dl->destroyActivity($id);

        return Redirect::to($_SESSION['back_link']);
    }

    public function confirmDestroy($id)
    {
        Log::debug('ConfirmDestroy_activity', ['id' => $id]);
        return $this->sedActivityView(self::DELETE, $id);
    }

}
