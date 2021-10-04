<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatoFatturazione extends Model
{
    use HasFactory;

    protected $table = 'stato_fatturazione';
    protected $fillable = ['descrizione_stato_fatturazione'];
    public $timestamps = false;
    public $incrementing = false;

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }
}
