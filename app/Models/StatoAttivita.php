<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatoAttivita extends Model
{
    use HasFactory;

    protected $table = 'stato_attivita';
    protected $fillable = ['descrizione_stato_attivita'];
    public $timestamps = false;
    public $incrementing = false;

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }
}
