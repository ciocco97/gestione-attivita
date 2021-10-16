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
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 0
        ]);

        Cliente::create([
            'nome' => 'Fondazione casa industria',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 1
        ]);

        Cliente::create([
            'nome' => 'AD control',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 1
        ]);

        Cliente::create([
            'nome' => 'La Piadineria',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 1
        ]);

        Cliente::create([
            'nome' => 'Costruttori edili riuniti',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 0
        ]);

        Cliente::create([
            'nome' => 'Fondazione Teresa Camplani',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 1
        ]);

        Cliente::create([
            'nome' => 'Bresciangrana',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 0
        ]);
    }
}
