<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\StatoCommessa;
use Illuminate\Database\Seeder;
use App\Models\Commessa;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c = Cliente::all()->first();
        $s = StatoCommessa::all()->first();
        Commessa::create([
            'descrizione_commessa' => '# Altra commessa',
            'cliente_id' => $c->id,
            'stato_commessa_id' => $s->id,
            'persona_id' => Persona::take(1)->get()->first()->id,
            'rapportino_commessa' => 0
        ]);
    }
}
