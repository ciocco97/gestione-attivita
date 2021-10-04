<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attivita extends Model
{
    use HasFactory;

    protected $table = 'attivita';

    protected $fillable = ['persona_id', 'commessa_id', 'data', 'ora_inizio',
        'ora_fine', 'durata', 'durata_fatturabile', 'luogo', 'descrizione_attivita', 'note_interne',
        'stato_attivita_id', 'rapportino_attivita'];

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

}
