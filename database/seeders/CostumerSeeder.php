<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class CostumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'nome' => '# Altro cliente',
            'rapportino_cliente' => 0
        ]);

//        Cliente::create([
//            'nome' => 'Fondazione casa industria',
//            'rapportino_cliente' => 1
//        ]);
//
//        Cliente::create([
//            'nome' => 'AD control',
//            'rapportino_cliente' => 1
//        ]);
//
//        Cliente::create([
//            'nome' => 'La Piadineria',
//            'rapportino_cliente' => 1
//        ]);
//
//        Cliente::create([
//            'nome' => 'Costruttori edili riuniti',
//            'rapportino_cliente' => 0
//        ]);
//
//        Cliente::create([
//            'nome' => 'Fondazione Teresa Camplani',
//            'rapportino_cliente' => 1
//        ]);
//
//        Cliente::create([
//            'nome' => 'Bresciangrana',
//            'rapportino_cliente' => 0
//        ]);
    }
}
