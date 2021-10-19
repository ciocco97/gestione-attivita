<?php

namespace Database\Seeders;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoAttivita;
use App\Models\StatoCommessa;
use App\Models\StatoFatturazione;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            OrderStateSeeder::class,
            BillingStateSeeder::class,
            ActivityStateSeeder::class,
            CostumerSeeder::class,
            OrderSeeder::class
        ]);

        $stati_commessa = StatoCommessa::all();
        $stati_attivita = StatoAttivita::all();
        $stati_fatturazione = StatoFatturazione::all();
        $persone = Persona::all();
        $clienti = Cliente::all();

        foreach ($stati_attivita as $sa) {
            $c = Cliente::find(rand(1, $clienti->count()));
            $co = Commessa::factory()
                ->count(1)
                ->state([
                    'cliente_id' => $c->id,
                    'persona_id' => 1,
                    'stato_commessa_id' => 1
                ]);
            foreach ($stati_fatturazione as $sf) {
                foreach ($persone as $p) {
                    $co->has(
                        Attivita::factory()
                            ->count(1)->state([
                                'persona_id' => $p->id,
                                'stato_attivita_id' => $sa->id,
                                'stato_fatturazione_id' => $sf->id
                            ])

                    )
                        ->create();
                }
            }
        }

    }
}
