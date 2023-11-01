<?php

namespace App\Http\Controllers;

use App\DataLayer;
use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoCommessa;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class CostumerController extends Controller
{

    public function indexCostumerView($costumers_infos)
    {
        $_SESSION['previous_url'] = url()->current();
        Log::debug('indexCostumerView');

        $costumers = Cliente::listCostumer();
        $order_states = StatoCommessa::listOrderStates();
        return view('costumer.commercial')
            ->with('order_states', $order_states)->with('costumers', $costumers)
            ->with('costumers_infos', $costumers_infos)
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

        $costumers_infos = $this->getCostumerInfos();

        $_SESSION['current_page'] = Shared::PAGES['COMMERCIAL'];
        return $this->indexCostumerView($costumers_infos);
    }

    public function filterPost(Request $request)
    {
        $costumer_id = $request->get('master_costumer_filter');
        $state_id = $request->get('master_order_state_filter');

        $costumer_id = $costumer_id == null ? -1 : $costumer_id;
        $state_id = $state_id == null ? -1 : $state_id;

        Log::debug('filterPost', [
            'costumer' => $costumer_id,
            'state' => $state_id
        ]);
        return redirect()->route(
            'costumer.filter.get',
            ['costumer' => $costumer_id, 'state' => $state_id]
        );
    }

    public function filter($costumer_id, $state_id)
    {
        $user_id = $_SESSION['user_id'];

        $costumer_id = $costumer_id == -1 ? null : $costumer_id;
        $state_id = $state_id == -1 ? null : $state_id;

        Log::debug('filter', [
            'costumer' => $costumer_id,
            'state' => $state_id,
        ]);

        Log::debug('Commercial filter');
        $costumers_infos = $this->getCostumerInfos($costumer_id, $state_id);

        return $this->indexCostumerView($costumers_infos);
    }

    private function getCostumerInfos($costumer_id_param = null, $state_id_param = null): \Illuminate\Support\Collection
    {
        $newCostumerQuery = Cliente::with([
            'commesse' => function ($query) use ($state_id_param) {
                $query->withCount('attivita')
                    ->withSum([
                        'attivita' => function ($query) {
                            $query->select(DB::raw('SUM(TIME_TO_SEC(durata))'));
                        }
                    ], 'durata')
                    ->withSum([
                        'attivita' => function ($query) {
                            $query->select(
                                DB::raw('SUM(CASE WHEN contabilizzata = 2 AND durata_fatturabile IS NOT NULL THEN TIME_TO_SEC(durata_fatturabile) 
                                WHEN contabilizzata = 2 AND durata_fatturabile IS NULL THEN TIME_TO_SEC(durata) ELSE 0 END)')
                            );
                        }
                    ], 'durata_fatturabile')
                    ->withCount([
                        'attivita as attivita_fatturabili_count' => function ($query) {
                            $query->where('contabilizzata', true);
                        }
                    ]);
                if ($state_id_param) {
                    $query->whereHas('statoCommessa', function ($query) use ($state_id_param) {
                        $query->where('id', $state_id_param);
                    });
                }
                $query->orderByDesc('attivita_sum_durata');
            }
        ])
            ->withCount('commesse');
        if ($costumer_id_param)
            $newCostumerQuery->where('id', $costumer_id_param);

        $costumers = $newCostumerQuery->get();

        echo $costumers;

        foreach ($costumers as $costumer) {
            $tot_durata = 0;
            $tot_durata_fatturabile = 0;
            foreach ($costumer->commesse as $order) {
                $order->attivita_sum_durata = round($order->attivita_sum_durata / 3600, 0);
                $order->attivita_sum_durata_fatturabile = round($order->attivita_sum_durata_fatturabile / 3600, 0);
                $tot_durata += $order->attivita_sum_durata;
                $tot_durata_fatturabile += $order->attivita_sum_durata_fatturabile;
            }
            $costumer->attivita_sum_durata_tot = $tot_durata;
            $costumer->attivita_sum_durata_fatturabile_tot = $tot_durata_fatturabile;
        }

        return $costumers;
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

        return view('costumer.show_costumer')
            ->with('method', $method)
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
        $report = $request->get('report_switch') == "on" ? 1 : 0;
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
        $report = $request->get('report_switch') == "on" ? 1 : 0;
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
