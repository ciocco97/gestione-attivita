<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
