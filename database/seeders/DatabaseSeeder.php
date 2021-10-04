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

//        foreach (Attivita::all() as $i) {
//            $i->delete();
//        }
//        foreach (Commessa::all() as $i) {
//            $i->delete();
//        }
//        foreach (Cliente::all() as $i) {
//            $i->delete();
//        }
//        foreach (Persona::all() as $i) {
//            $i->delete();
//        }
//        foreach (StatoCommessa::all() as $i) {
//            $i->delete();
//        }
//        foreach (StatoAttivita::all() as $i) {
//            $i->delete();
//        }

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

        foreach ($stati_attivita as $sa) {
            foreach ($stati_fatturazione as $sf) {
                foreach ($stati_commessa as $sc) {
                    foreach ($persone as $p) {
                        Cliente::factory()->count(1)
                            ->has(
                                Commessa::factory()
                                    ->count(2)
                                    ->state([
                                        'persona_id' => $p->id,
                                        'stato_commessa_id' => $sc->id
                                    ])
                                    ->has(
                                        Attivita::factory()
                                            ->count(3)
                                            ->state([
                                                'persona_id' => $p->id,
                                                'stato_attivita_id' => $sa->id,
                                                'stato_fatturazione_id' => $sf->id
                                        ]),
                                        'attivita'),
                                'commesse')
                            ->create();
                    }
                }
            }
        }

    }
}
