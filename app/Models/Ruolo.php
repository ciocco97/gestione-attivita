<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruolo extends Model
{
    use HasFactory;
    protected $table = 'ruolo';
    protected $fillable = ['id', 'descrizione_ruolo'];
    public $timestamps = false;
    public $incrementing = false;
    protected $hidden = ['pivot'];

    public function persone() {
        return $this->belongsToMany(Persona::class, 'persona_ruolo');
    }

}
