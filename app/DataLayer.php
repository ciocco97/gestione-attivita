<?php

namespace App;

use App\Models\Attivita;
use App\Models\AttivitaInfo;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoAttivita;
use App\Models\StatoCommessa;
use Faker\Provider\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use mysql_xdevapi\Statement;

class DataLayer
{
    public function validUser($email, $password, $user_id = -1)
    {
        if ($email != null) {
            $persona = Persona::where('email', $email)->where('password', $password)->get()->first();
            if (isset($persona)) {
                return $persona;
            } else {
                return false;
            }
        } else {
            $persona = Persona::find($user_id)->where('password', $password)->get()->first();
            if (isset($persona)) {
                return $persona;
            } else {
                return false;
            }
        }
    }

    public function changePassword($user_id, $password) {
        $user = Persona::find($user_id);
        $user->password = $password;
        $user->save();
    }

    public function listActiveActivityForActivityTableByUserID(int $user_id, $start_date)
    {
        return DB::table('attivita')
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
            ->join('stato_attivita', 'attivita.stato_attivita_id', '=', 'stato_attivita.id')
            ->selectRaw('attivita.id,
                                    attivita.descrizione_attivita,
                                    attivita.data,
                                    cliente.nome AS nome_cliente,
                                    commessa.descrizione_commessa,
                                    attivita.ora_inizio,
                                    attivita.ora_fine,
                                    attivita.durata,
                                    stato_attivita.descrizione_stato_attivita,
                                    attivita.rapportino_attivita,
                                    cliente.rapportino_cliente,
                                    commessa.rapportino_commessa,
                                    stato_commessa.id AS stato_commessa_id,
                                    stato_commessa.descrizione_stato_commessa,
                                    attivita.persona_id')
            ->where('stato_commessa.descrizione_stato_commessa', '=', 'aperta')
            ->where('attivita.persona_id', '=', $user_id)
            ->where('attivita.data', '>=', $start_date)
            ->orderBy('data', 'desc')
            ->get();
    }

    public function filterActiveActivityForActivityTableByUserID(int $user_id, $start_date, $end_date, $costumer, $state, $date)
    {
        $basic_query = DB::table('attivita')
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
            ->join('stato_attivita', 'attivita.stato_attivita_id', '=', 'stato_attivita.id')
            ->selectRaw('attivita.id,
                                    attivita.descrizione_attivita,
                                    attivita.data,
                                    cliente.nome AS nome_cliente,
                                    commessa.descrizione_commessa,
                                    attivita.ora_inizio,
                                    attivita.ora_fine,
                                    attivita.durata,
                                    stato_attivita.descrizione_stato_attivita,
                                    attivita.rapportino_attivita,
                                    cliente.rapportino_cliente,
                                    commessa.rapportino_commessa,
                                    stato_commessa.id AS stato_commessa_id,
                                    stato_commessa.descrizione_stato_commessa,
                                    attivita.persona_id')
            ->where('stato_commessa.descrizione_stato_commessa', '=', 'aperta')
            ->where('attivita.persona_id', '=', $user_id)
            ->orderBy('data', 'desc');
        if ($date != null) {
            $basic_query->where('attivita.data', '=', $date);
        } else if ($start_date != null) {
            $basic_query->where('attivita.data', '>=', $start_date);
            if ($end_date != null) {
                $basic_query->where('attivita.data', '<=', $end_date);
            }
        }
        if ($costumer != null) {
            $basic_query->where('cliente.id', '=', $costumer);
        }
        if ($state != null) {
            $basic_query->where('stato_attivita.id', '=', $state);
        }
        return $basic_query->get();
    }

    public function getActivityByActivityAndUserID(int $activity_id, int $user_id)
    {
        return Attivita::where('persona_id', $user_id)->where('id', $activity_id)->get()->first();
    }

    public function storeActivity($user_id, $order_id, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state)
    {
        Attivita::create([
            'persona_id' => $user_id,
            'commessa_id' => $order_id,
            'data' => $date,
            'ora_inizio' => $startTime,
            'ora_fine' => $endTime,
            'durata' => $duration,
            'luogo' => $location,
            'descrizione_attivita' => $description,
            'note_interne' => $internalNotes,
            'stato_attivita_id' => $state,
            'rapportino_attivita' => 0
        ]);
    }

    public function updateActivity($activity_id, $user_id, $order_id, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state)
    {
        Attivita::find($activity_id)->update([
            'persona_id' => $user_id,
            'commessa_id' => $order_id,
            'data' => $date,
            'ora_inizio' => $startTime,
            'ora_fine' => $endTime,
            'durata' => $duration,
            'luogo' => $location,
            'descrizione_attivita' => $description,
            'note_interne' => $internalNotes,
            'stato_attivita_id' => $state
        ]);
    }

    public function destroyActivity($id)
    {
        Attivita::destroy($id);
    }

    public function listActivityState()
    {
        return StatoAttivita::all();
    }

    public function listActivityStateForTech()
    {
        return StatoAttivita::where('descrizione_stato_attivita', '!=', 'approvata')->get();
    }

    public function listCostumer()
    {
        return Cliente::all();
    }

    public function listActiveCostumer()
    {
        return DB::table('cliente')
            ->select('cliente.id', 'cliente.nome')
            ->join('commessa', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
            ->where('stato_commessa.descrizione_stato_commessa', '=', 'aperta')
            ->distinct()
            ->get();
    }

    public function listActiveCostumerByUserID()
    {
        return DB::table('cliente')
            ->select('cliente.id', 'cliente.nome')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('commessa')
                    ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
                    ->whereColumn('commessa.cliente_id', 'cliente.id')
                    ->where('stato_commessa.descrizione_stato_commessa', '=', 'aperta')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('attivita')
                            ->whereColumn('attivita.commessa_id', 'commessa.id')
                            ->where('attivita.persona_id', '=', $_SESSION['user_id']);
                    });
            })

            ->distinct()
            ->get();
    }

    public function getCostumerByOrderID($order_id)
    {
        return Commessa::find($order_id)->cliente()->get()->first();
    }

    public function isCostumerActive(int $costumer_id)
    {
        return (Cliente::find($costumer_id)->commesse()->count() > 0);
    }

    public function listOrder()
    {
        return Commessa::all();
    }

    public function listActiveOrder()
    {
        return StatoCommessa::where('descrizione_stato_commessa', 'aperta')->first()->commesse()->get();
    }

    public function listActiveOrderByCostumerID(int $costumer_id)
    {
        return StatoCommessa::where('descrizione_stato_commessa', 'aperta')
            ->first()->commesse()->where('cliente_id', $costumer_id)->get();
    }

}
