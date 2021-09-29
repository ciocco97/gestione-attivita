<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    protected $fillable = ['nome', 'cognome', 'email', 'password', 'token', 'istante_creazione_token'];
    public $timestamps = false;
    protected $hidden = ['pivot', 'password'];

    public function attivita() {
        return $this->belongsTo(Attivita::class);
    }

    public function sottoposti() {
        return $this->belongsToMany(Persona::class, 'manager_sottoposto', 'manager_id', 'sottoposto_id');
    }

    public function manager() {
        return $this->belongsToMany(Persona::class, 'manager_sottoposto', 'sottoposto_id', 'manager_id');
    }

    public function ruoli()
    {
        return $this->belongsToMany(Ruolo::class, 'persona_ruolo');
    }
}
