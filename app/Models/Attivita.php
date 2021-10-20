<?php

namespace App\Models;

use App\Mail\ActivityReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Attivita extends Model
{
    use HasFactory;

    protected $table = 'attivita';

    protected $fillable = ['persona_id', 'commessa_id', 'data', 'ora_inizio',
        'ora_fine', 'durata', 'durata_fatturabile', 'luogo', 'descrizione_attivita', 'note_interne',
        'stato_attivita_id', 'rapportino_attivita', 'stato_fatturazione_id', 'contabilizzata'];

    // Connections

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function commessa()
    {
        return $this->belongsTo(Commessa::class);
    }

    public function statoAttivita()
    {
        return $this->belongsTo(StatoAttivita::class);
    }

    public function statoFatturazione()
    {
        return $this->belongsTo(StatoFatturazione::class);
    }



    public static function getActivityByActivityAndUserID(int $activity_id, int $user_id)
    {
        $activity = Attivita::find($activity_id);
        if (Persona::haveShowPermissionOnActivity($user_id, $activity)) {
            return $activity;
        } else {
            return null;
        }
    }

    public static function storeActivity($user_id, $order_id, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state)
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

    public static function updateActivity($user_id, $activity_id, $order_id, $date, $startTime, $endTime, $duration, $location, $description, $internalNotes, $state): bool
    {
        $activity = Attivita::find($activity_id);
        if (Persona::haveUpdatePermissionOnActivity($user_id, $activity)) {
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
            return true;
        }
        return false;
    }

    public static function destroyActivity($id, $user_id): bool
    {
        $activity = Attivita::find($id);
        if (Persona::haveUpdatePermissionOnActivity($user_id, $activity)) {
            Attivita::destroy($id);
            return true;
        }
        return false;
    }


    public static function basicActivityQuery(): \Illuminate\Database\Query\Builder
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
                                    attivita.contabilizzata,
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

    public static function basicActiveActivityQuery(): \Illuminate\Database\Query\Builder
    {
        return Attivita::basicActivityQuery()
            ->where('stato_commessa.id', '=', 1);
    }

    public static function listActiveActivityByUserID(int $user_id, $start_date): \Illuminate\Support\Collection
    {
        return Attivita::basicActiveActivityQuery()
            ->where('attivita.persona_id', '=', $user_id)
            ->where('attivita.data', '>=', $start_date)
            ->get();
    }

    public static function filterActiveActivityByUserID(int $user_id, $start_date, $end_date, $costumer, $state, $date, $team_selected_ids): \Illuminate\Support\Collection
    {
        $basic_query = Attivita::basicActiveActivityQuery();
        $team_ids = Persona::listTeamIDS($user_id);

        if ($team_selected_ids != null && count(array_intersect($team_selected_ids, $team_ids)) == count($team_selected_ids)) {
            $basic_query->whereIn('attivita.persona_id', $team_selected_ids);
        } else {
            $basic_query->where('attivita.persona_id', '=', $user_id);
        }

        return Attivita::addFilterToQuery($basic_query, $start_date, $end_date, $costumer, $state, $date)->get();
    }

    public static function updateActivityReport($activity_id, $user_id, bool $sent): bool
    {
        $activity = Attivita::find($activity_id);
        if (Persona::haveUpdatePermissionOnActivity($user_id, $activity)) {
            $val = $sent ? 1 : 0;
            $activity->update([
                'rapportino_attivita' => $val
            ]);
            return true;
        }
        return false;
    }

    public static function sendActivityReport($user_id, $activity_id): bool
    {
        $activity = Attivita::getActivityForActivityReport($activity_id, $user_id);

        Mail::to($activity->email)
            ->queue(new ActivityReport($activity));

        return Attivita::updateActivityReport($activity_id, $user_id, true);

    }


    public static function listActiveActivityForManagerTableByUserID(int $user_id, $start_date): \Illuminate\Support\Collection
    {
        $query = Attivita::basicActiveActivityQuery();
        $team = Persona::listTeamIDS($user_id);
        $query->where('attivita.data', '>=', $start_date);
        return $query->whereIn('attivita.persona_id', $team)
            ->where('data', '>=', $start_date)
            ->get();
    }

    public static function changeActivityBillableDuration($user_id, $activity_id, $billable_duration): bool
    {
        $activity = Attivita::find($activity_id);
        if (Persona::haveManagerPermissionOnActivity($user_id, $activity)) {
            $activity->durata_fatturabile = $billable_duration;
            $activity->save();
            return true;
        }
        return false;
    }

    public static function stateUpdateByActivityIDS($user_id, $ids, $state): bool
    {
        $team = Persona::listTeamIDS($user_id);
        if ($state != 4) {
            array_push($team, $user_id);
        }
        Attivita::whereIn('persona_id', $team)
            ->whereIn('id', $ids)
            ->update(['stato_attivita_id' => $state]);
        return true;
    }


    public static function basicQueryForListApprovedActivity(): \Illuminate\Database\Query\Builder
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
                                    attivita.contabilizzata,
                                    cliente.id AS cliente_id,
                                    cliente.nome AS nome_cliente,
                                    commessa.descrizione_commessa,
                                    persona.nome,
                                    stato_fatturazione.descrizione_stato_fatturazione')
            ->where('attivita.stato_attivita_id', 4)
            ->orderBy('data', 'desc')
            ->orderBy('ora_inizio', 'desc');
    }

    public static function listAdministrativeActivities($start_date): \Illuminate\Support\Collection
    {
        return Attivita::basicQueryForListApprovedActivity()
            ->where('attivita.data', '>=', $start_date)->get();
    }

    public static function filterAdministrativeActivities($start_date, $end_date, $costumer, $state, $date, $user_selected_id, $billing_state, $accounted): \Illuminate\Support\Collection
    {
        $basic_query = Attivita::basicQueryForListApprovedActivity();
        if ($user_selected_id != null) {
            $basic_query->where('attivita.persona_id', $user_selected_id);
        }
        if ($accounted != null) {
            $basic_query->where('attivita.contabilizzata', $accounted);
        } else if ($billing_state != null) {
            $basic_query->where('attivita.stato_fatturazione_id', $billing_state);
        }
        return Attivita::addFilterToQuery($basic_query, $start_date, $end_date, $costumer, $state, $date)->get();
    }

    public static function addFilterToQuery($query, $start_date, $end_date, $costumer, $state, $date): \Illuminate\Database\Query\Builder
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

    public static function changeActivityBillingState($user_id, $activity_id, $billing_state): bool
    {
        $activity = Attivita::find($activity_id);

        if (Persona::haveManagerPermissionOnActivity($user_id, $activity)) {
            $activity->stato_fatturazione_id = $billing_state;
            $activity->save();
            return true;
        }

        return false;
    }

    public static function accountedUpdateByActivityIDS($user_id, $ids, $state): bool
    {
        if (Persona::haveAdministrativePermissionOnActivity($user_id)) {
            Attivita::whereIn('id', $ids)
                ->update(['contabilizzata' => $state]);
            return true;
        }
        return false;
    }


    public static function getActivityForActivityReport(int $activity_id, int $user_id)
    {
        $activity = Attivita::find($activity_id);
        if (Persona::haveTechnicianPermissionOnActivity($user_id, $activity)
            || Persona::haveManagerPermissionOnActivity($user_id, $activity)) {
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

}
