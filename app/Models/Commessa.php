<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

//    public function statoFatturazioneDefault()
//    {
//        return $this->hasOne(StatoFatturazione::class);
//    }

//    public function isOpened()
//    {
//        return $this->statoCommessa()->descrizione_stato_commessa == 'aperta';
//    }
//
//    public function isCancelled()
//    {
//        return $this->statoCommessa()->descrizione_stato_commessa == 'annullata';
//    }
//
//    public function getStateName()
//    {
//        return $this->statoCommessa()->get()->first()->descrizione_stato_commessa;
//    }

}
