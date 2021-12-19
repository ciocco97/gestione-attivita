<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $fillable = ['nome', 'email', 'rapportino_cliente'];
    public $timestamps = false;

    public function commesse()
    {
        return $this->hasMany(Commessa::class);
    }

    public function attivita()
    {
        return $this->hasManyThrough(Attivita::class, Commessa::class);
    }


    public static function listCostumer(int $costumer_id = null): \Illuminate\Support\Collection
    {
        $query = Cliente::orderBy('nome');
        if ($costumer_id != null) {
            $query->where('id', $costumer_id);
        }
        return $query->get();
    }

    public static function listActiveCostumer(): \Illuminate\Support\Collection
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

    public static function listActiveCostumerForCurrentUser(): \Illuminate\Support\Collection
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

    public static function getCostumerByID($costumer_id)
    {
        return Cliente::find($costumer_id);
    }

    public static function getCostumerByOrderID($order_id)
    {
        return Commessa::find($order_id)->cliente()->get()->first();
    }

    public static function isCostumerActive(int $costumer_id): bool
    {
        return (Cliente::find($costumer_id)->commesse()->count() > 0);
    }


    public static function storeCostumer($user_id, $name, $email, $report)
    {
        if (Persona::haveCommercialPermission($user_id)) {
            Cliente::create([
                'nome' => $name,
                'email' => $email,
                'rapportino_cliente' => $report
            ]);
        }
    }

    public static function updateCostumer($user_id, $costumer_id, $name, $email, $report): bool
    {
        $costumer = Cliente::find($costumer_id);
        if (Persona::haveCommercialPermission($user_id)) {
            $costumer->update([
                'nome' => $name,
                'email' => $email,
                'rapportino_cliente' => $report
            ]);
            return true;
        }
        return false;
    }

    public static function destroyCostumer($id, $user_id): bool
    {
        $num_order = Cliente::find($id)->commesse->count();
        if ($num_order < 1 && Persona::haveCommercialPermission($user_id)) {
            Cliente::destroy($id);
            return true;
        }
        return false;
    }


    public static function getNumActivitiesPerCostumer($accounted = null): \Illuminate\Support\Collection
    {
        $query = DB::table('attivita')
            ->select(DB::raw('cliente.id AS cliente_id, count(*) AS attivita_num'))
            ->join('commessa', 'attivita.commessa_id', '=', 'commessa.id')
            ->join('cliente', 'commessa.cliente_id', '=', 'cliente.id')
            ->groupBy('cliente.id');
        if ($accounted) {
            $query->where('attivita.contabilizzata', '=', '2');
        }
        return $query->get();
    }

    public static function getNumOrdersPerCostumer(): \Illuminate\Support\Collection
    {
        $query = DB::table('cliente')
            ->select(DB::raw('cliente.id AS cliente_id, count(*) AS commesse_num'))
            ->join('commessa', 'cliente.id', '=', 'commessa.cliente_id')
            ->groupBy('cliente.id');
        return $query->get();
    }

}
