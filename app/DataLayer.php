<?php

namespace App;

use App\Models\Attivita;
use App\Models\AttivitaInfo;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\Ruolo;
use App\Models\StatoAttivita;
use App\Models\StatoCommessa;
use App\Models\StatoFatturazione;
use Carbon\Carbon;
use Faker\Provider\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use mysql_xdevapi\Statement;

class DataLayer
{
    public function listUsers()
    {
        return Persona::all();
    }

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

    public function changePassword($user_id, $password_md5)
    {
        $user = Persona::find($user_id);
        $user->password = $password_md5;
        $user->save();
        Log::debug('password salvata correttamente');
    }

    public function storeToken($email, $token)
    {
        return Persona::where('email', $email)
            ->update([
                'token' => md5($token),
                'istante_creazione_token' => Carbon::now()->toDateTimeString()
            ]);
    }

    public function validToken($email, $token)
    {
        $user = Persona::where('email', $email)->get()->first();
        $before = new Carbon($user->istante_creazione_token);
        $now = Carbon::now();
        if ($user->token == md5($token) && $before->diffInSeconds($now) < 240) {
            Log::debug('Token valido');
            return true;
        }
        Log::debug('Token non valido');
        return false;
    }

    public function resetPassword($email, $password)
    {
        $user_id = Persona::where('email', $email)->get()->first()->id;
        $this->changePassword($user_id, $password);
    }

    private function basicActivityQuery(): \Illuminate\Database\Query\Builder
    {
        return DB::table('attivita')
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
            ->join('stato_attivita', 'attivita.stato_attivita_id', '=', 'stato_attivita.id')
            ->join('persona', 'attivita.persona_id', '=', 'persona.id')
            ->join('stato_fatturazione', 'attivita.stato_fatturazione_id', '=', 'stato_fatturazione.id')
            ->selectRaw('attivita.id,
                                    attivita.stato_attivita_id,
                                    attivita.descrizione_attivita,
                                    attivita.data,
                                    attivita.ora_inizio,
                                    attivita.ora_fine,
                                    attivita.durata,
                                    attivita.rapportino_attivita,
                                    attivita.persona_id,
                                    attivita.stato_fatturazione_id,
                                    attivita.durata_fatturabile,
                                    attivita.fatturata,
                                    cliente.nome AS nome_cliente,
                                    cliente.rapportino_cliente,
                                    commessa.descrizione_commessa,
                                    commessa.rapportino_commessa,
                                    stato_commessa.id AS stato_commessa_id,
                                    stato_commessa.descrizione_stato_commessa,
                                    stato_attivita.descrizione_stato_attivita,
                                    stato_fatturazione.descrizione_stato_fatturazione,
                                    persona.nome')
            ->orderBy('data', 'desc')
            ->orderBy('ora_inizio', 'desc');
    }

    private function basicActiveActivityQuery(): \Illuminate\Database\Query\Builder
    {
        return $this->basicActivityQuery()
            ->where('stato_commessa.id', '=', 1);
    }

    public function listActiveActivityByUserID(int $user_id, $start_date): \Illuminate\Support\Collection
    {
        return $this->basicActiveActivityQuery()
            ->where('attivita.persona_id', '=', $user_id)
            ->where('attivita.data', '>=', $start_date)
            ->get();
    }

    private function basicQueryForListApprovedActivity()
    {
        return DB::table('attivita')
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_attivita', 'attivita.stato_attivita_id', '=', 'stato_attivita.id')
            ->join('persona', 'attivita.persona_id', '=', 'persona.id')
            ->join('stato_fatturazione', 'attivita.stato_fatturazione_id', '=', 'stato_fatturazione.id')
            ->selectRaw('attivita.id,
                                    attivita.descrizione_attivita,
                                    attivita.data,
                                    attivita.ora_inizio,
                                    attivita.durata,
                                    attivita.durata_fatturabile,
                                    attivita.persona_id,
                                    attivita.stato_fatturazione_id,
                                    attivita.fatturata,
                                    cliente.id AS cliente_id,
                                    cliente.nome AS nome_cliente,
                                    commessa.descrizione_commessa,
                                    persona.nome,
                                    stato_fatturazione.descrizione_stato_fatturazione')
            ->where('attivita.stato_attivita_id', 4)
            ->orderBy('data', 'desc')
            ->orderBy('ora_inizio', 'desc');
    }

    public function listApprovedActivity($start_date)
    {
        $approved_activities_query = $this->basicQueryForListApprovedActivity()
            ->where('attivita.data', '>=', $start_date);
        $not_bill_activities_num_query = DB::table('attivita')
            ->select(DB::raw('cliente.id AS cliente_id, count(*) AS attivita_num'))
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->join('stato_fatturazione', 'attivita.stato_fatturazione_id', '=', 'stato_fatturazione.id')
            ->where('attivita.data', '>=', $start_date)
            ->where('attivita.stato_fatturazione_id', '=', '4')
            ->groupBy('cliente.id');
        return array(
            $approved_activities_query->get(),
            $not_bill_activities_num_query->get()
        );
    }

    // Ritorna lista di sottoposti + manager
    public function listTeam(int $user_id)
    {
        $user = Persona::find($user_id);
        return $user->sottoposti()->get();
    }

    public function listTeamIDS(int $user_id)
    {
        return $this->listTeam($user_id)->pluck('id')->toArray();
    }

    public function listUserRoles(int $user_id)
    {
        $user = Persona::find($user_id);
        $roles = $user->ruoli()->get();
        $team = $this->listTeam($user_id);
        if ($team->count() > 0) {
            $manager_role = new Ruolo();
            $manager_role->id = 3;
            $manager_role->descrizione_ruolo = 'manager';
            $roles->push($manager_role);
        }
        return $roles->pluck('id');
    }

    public function listActiveActivityForManagerTableByUserID(int $user_id, $start_date)
    {
        $query = $this->basicActiveActivityQuery();
        $team = $this->listTeamIDS($user_id);
        $query->where('attivita.data', '>=', $start_date);
        return $query->whereIn('attivita.persona_id', $team)
            ->where('data', '>=', $start_date)
            ->get();
    }

    public function filterActiveActivityByUserID(int $user_id, $start_date, $end_date, $costumer, $state, $date, $team_selected_ids)
    {
        $basic_query = $this->basicActiveActivityQuery();
        $team_ids = $this->listTeamIDS($user_id);

        if ($team_selected_ids != null && count(array_intersect($team_selected_ids, $team_ids)) == count($team_selected_ids)) {
            $basic_query->whereIn('attivita.persona_id', $team_selected_ids);
        } else {
            $basic_query->where('attivita.persona_id', '=', $user_id);
        }

        return $this->addFilterToQuery($basic_query, $start_date, $end_date, $costumer, $state, $date)->get();
    }

    public function filterAdministrativeActivities($start_date, $end_date, $costumer, $state, $date, $user_selected_id, $billed)
    {
        $basic_query = $this->basicQueryForListApprovedActivity();
        if ($user_selected_id != null) {
            $basic_query->where('attivita.persona_id', $user_selected_id);
        }
        if ($billed != null) {
            $basic_query->where('attivita.stato_fatturazione_id', $billed);
        }
        return $this->addFilterToQuery($basic_query, $start_date, $end_date, $costumer, $state, $date)->get();
    }

    public function addFilterToQuery($query, $start_date, $end_date, $costumer, $state, $date)
    {

        if ($date != null) {
            $query->where('attivita.data', '=', $date);
        } else if ($start_date != null) {
            $query->where('attivita.data', '>=', $start_date);
            if ($end_date != null) {
                $query->where('attivita.data', '<=', $end_date);
            }
        }
        if ($costumer != null) {
            $query->where('cliente.id', '=', $costumer);
        }
        if ($state != null) {
            $query->where('stato_attivita.id', '=', $state);
        }
        return $query;
    }

    public function listAdministrativeActivities($start_date)
    {
        return $this->basicQueryForListApprovedActivity()
            ->where('attivita.data', '>=', $start_date)->get();
    }

    public function haveUpdatePermissionOnActivity($user_id, $activity)
    {
        return $this->haveTechnicianPermissionOnActivity($user_id, $activity)
            || $this->haveManagerPermissionOnActivity($user_id, $activity);
    }

    public function haveShowPermissionOnActivity($user_id, $activity)
    {
        return $this->haveTechnicianPermissionOnActivity($user_id, $activity)
            || $this->haveManagerPermissionOnActivity($user_id, $activity)
            || $this->haveAdministrativePermissionOnActivity($user_id);
    }

    public function haveTechnicianPermissionOnActivity($user_id, $activity)
    {
        $activity_user_id = $activity->persona_id;
        return $activity_user_id == $user_id;
    }

    public function haveManagerPermissionOnActivity($user_id, $activity)
    {
        $activity_user_id = $activity->persona_id;
        return in_array($activity_user_id, $this->listTeamIDS($user_id));
    }

    public function haveAdministrativePermissionOnActivity($user_id)
    {
        return in_array(1, $this->listUserRoles($user_id)->toArray());
    }

    public function getActivityByActivityAndUserID(int $activity_id, int $user_id)
    {
        $activity = Attivita::find($activity_id);
        if ($this->haveShowPermissionOnActivity($user_id, $activity)) {
            return $activity;
        } else {
            return null;
        }
    }

    public function getActivityForActivityReport(int $activity_id, int $user_id)
    {
        $activity = Attivita::find($activity_id);
        if ($this->haveTechnicianPermissionOnActivity($user_id, $activity)
            || $this->haveManagerPermissionOnActivity($user_id, $activity)) {
            return DB::table('attivita')
                ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
                ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
                ->join('persona', 'attivita.persona_id', '=', 'persona.id')
                ->where('attivita.id', $activity_id)
                ->selectRaw('attivita.descrizione_attivita,
                                        persona.nome,
                                        attivita.durata,
                                        cliente.email')
                ->get()->first();
        } else {
            return null;
        }
    }

    public function listBillingStates()
    {
        return StatoFatturazione::all();
    }

    public function listManagerBillingStates()
    {
        return StatoFatturazione::all()->reject(function ($val) {
            return $val->id == 4;
        });
    }

    public function changeActivityBillingState($user_id, $activity_id, $billing_state)
    {
        $activity = Attivita::find($activity_id);
        $user = Persona::find($user_id);
        $access = false;
        $administrative_permission = $this->haveAdministrativePermissionOnActivity($user_id);
        if ($billing_state == 4) {
            $access = $administrative_permission;
        } else {
            $access = $administrative_permission || $this->haveManagerPermissionOnActivity();
        }
        if ($access) {
            $activity->stato_fatturazione_id = $billing_state;
            $activity->save();
        }
    }

    public function billingStateUpdateByActivityIDS($user_id, $ids, $state)
    {
        if ($this->haveAdministrativePermissionOnActivity($user_id)) {
            Attivita::whereIn('id', $ids)
                ->update(['fatturata' => $state]);
        }
    }

    public function changeActivityBillableDuration($user_id, $activity_id, $billable_duration)
    {
        $activity = Attivita::find($activity_id);
        $user = Persona::find($user_id);
        $user_roles = $user->ruoli()->get()->pluck('id')->toArray();
        if ($this->haveManagerPermissionOnActivity($user_id, $activity)) {
            $activity->durata_fatturabile = $billable_duration;
            $activity->save();
        }
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

    public function updateActivity($user_id, $activity_id, $order_id, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state)
    {
        $activity = Attivita::find($activity_id);
        if ($this->haveUpdatePermissionOnActivity($user_id, $activity)) {
            $activity->update([
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
    }

    public function updateActivityReport($activity_id, $user_id, bool $sent)
    {
        $activity = Attivita::find($activity_id);
        if ($this->haveUpdatePermissionOnActivity($user_id, $activity)) {
            $val = $sent ? 1 : 0;
            $activity->update([
                'rapportino_attivita' => $val
            ]);
        }
    }

    public function destroyActivity($id, $user_id)
    {
        $activity = Attivita::find($id);
        if ($this->haveUpdatePermissionOnActivity($user_id, $activity)) {
            Attivita::destroy($id);
        }
    }

    public function listActivityState()
    {
        return StatoAttivita::all();
    }

    public function listActivityStateForTech()
    {
        return StatoAttivita::where('id', '!=', 4)->get();
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
            ->where('stato_commessa.id', '=', 1)
            ->distinct()
            ->orderBy('cliente.nome')
            ->get();
    }

    public function listActiveCostumerForCurrentUser()
    {
        return DB::table('cliente')
            ->select('cliente.id', 'cliente.nome')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('commessa')
                    ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
                    ->whereColumn('commessa.cliente_id', 'cliente.id')
                    ->where('stato_commessa.id', '=', 1)
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('attivita')
                            ->whereColumn('attivita.commessa_id', 'commessa.id')
                            ->where('attivita.persona_id', '=', $_SESSION['user_id']);
                    });
            })
            ->distinct()
            ->orderBy('cliente.nome')
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
        return StatoCommessa::where('id', 1)->first()->commesse()->get();
    }

    public function listActiveOrderByCostumerID(int $costumer_id)
    {
        return StatoCommessa::where('id', 1)
            ->first()->commesse()->where('cliente_id', $costumer_id)->get();
    }

    public function stateUpdateByActivityIDS($user_id, $ids, $state)
    {
        $team = $this->listTeamIDS($user_id);
        array_push($team, $user_id);
        Attivita::whereIn('persona_id', $team)
            ->whereIn('id', $ids)
            ->update(['stato_attivita_id' => $state]);
    }

}
