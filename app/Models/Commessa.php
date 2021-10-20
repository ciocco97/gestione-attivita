<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Commessa extends Model
{
    use HasFactory;

    protected $table = 'commessa';
    protected $fillable = ['descrizione_commessa', 'cliente_id', 'stato_commessa_id',
        'persona_id', 'stato_fatturazione_dafault_id', 'rapportino_commessa'];
    public $timestamps = false;

    // Connections

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function statoCommessa()
    {
        return $this->belongsTo(StatoCommessa::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }


    public static function getOrderByID($order_id)
    {
        return Commessa::find($order_id);
    }

    public static function listOrder(): \Illuminate\Support\Collection
    {
        return Commessa::all();
    }

    public static function listActiveOrder(): \Illuminate\Support\Collection
    {
        return StatoCommessa::find(1)->commesse()->get();
    }

    public static function listActiveOrderByCostumerID(int $costumer_id): \Illuminate\Support\Collection
    {
        return StatoCommessa::find(1)->commesse()->where('cliente_id', $costumer_id)->get();
    }

    public static function listOrderInfos(): \Illuminate\Support\Collection
    {
        $orders = DB::table('commessa')
            ->select(DB::raw('commessa.id,
                commessa.descrizione_commessa,
                commessa.cliente_id,
                commessa.stato_commessa_id,
                commessa.persona_id,
                commessa.rapportino_commessa,
                stato_commessa.descrizione_stato_commessa'
            ))
            ->join('stato_commessa', 'commessa.stato_commessa_id', '=', 'stato_commessa.id')
            ->orderBy('commessa.descrizione_commessa')
            ->get();
        $nums = DB::table('commessa')
            ->select(DB::raw('commessa.id, count(*) AS num_attivita'))
            ->join('attivita', 'commessa.id', '=', 'attivita.commessa_id')
            ->groupBy('commessa.id')
            ->get();
        foreach ($orders as $order) {
            $order_id = $order->id;
            $order->num_attivita = $nums->filter(function ($num) use (&$order_id) {
                return $order_id == $num->id;
            })->pluck('num_attivita')->first();
            $order->num_attivita = $order->num_attivita == null ? 0 : $order->num_attivita;
        }
        return $orders;
    }


    public static function storeOrder($user_id, $description, $costumer, $state, $report): bool
    {
        if (Persona::haveCommercialPermission($user_id)) {
            Commessa::create([
                'persona_id' => $user_id,
                'descrizione_commessa' => $description,
                'cliente_id' => $costumer,
                'stato_commessa_id' => $state,
                'rapportino_commessa' => $report,
            ]);
            return true;
        }
        return false;
    }

    public static function updateOrder($user_id, $order_id, $description, $costumer, $state, $report): bool
    {
        $order = Commessa::find($order_id);
        if (Persona::haveCommercialPermission($user_id)) {
            $order->update([
                'descrizione_commessa' => $description,
                'cliente_id' => $costumer,
                'stato_commessa_id' => $state,
                'rapportino_commessa' => $report,
            ]);
            return true;
        }
        return false;
    }

    public static function destroyOrder($id, $user_id): bool
    {
        if (Persona::haveCommercialPermission($user_id)) {
            Commessa::destroy($id);
            return true;
        }
        return false;
    }


    public static function changeOrderReport($user_id, $order_id, $order_report): bool
    {
        if (Persona::haveCommercialPermission($user_id)) {
            $order = Commessa::find($order_id);
            $order->rapportino_commessa = $order_report;
            $order->save();
            return true;
        }
        return false;
    }

    public static function changeOrderState($user_id, $order_id, $order_state): bool
    {
        if (Persona::haveCommercialPermission($user_id)) {
            $order = Commessa::find($order_id);
            $order->stato_commessa_id = $order_state;
            $order->save();
            return true;
        }
        return false;
    }

}
