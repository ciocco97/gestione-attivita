<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttivitaInfo extends Model
{
    use HasFactory;

    protected $table = 'attivita_info';
    protected $primaryKey = 'id';

    public $timestamps = false;

}
