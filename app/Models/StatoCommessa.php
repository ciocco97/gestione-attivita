<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatoCommessa extends Model
{
    use HasFactory;

    protected $table = 'stato_commessa';
    protected $fillable = ['id', 'descrizione_stato_commessa'];
    public $timestamps = false;
    public $incrementing = false;

    public function commesse()
    {
        return $this->hasMany(Commessa::class);
    }

    public static function listOrderStates()
    {
        return StatoCommessa::all();
    }

}
