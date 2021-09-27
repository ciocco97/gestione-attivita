<?php

namespace Database\Seeders;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\StatoAttivita;
use App\Models\StatoCommessa;
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
            ActivityStateSeeder::class,
            CostumerSeeder::class,
            OrderSeeder::class
        ]);

        $stati_commessa = StatoCommessa::all();
        $stati_attivita = StatoAttivita::all();
        $persone = Persona::all();

        foreach ($persone as $p) {
            foreach ($stati_commessa as $sc) {
                foreach ($stati_attivita as $sa) {
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
                                        ->count(1)
                                        ->state([
                                            'persona_id' => $p->id,
                                            'stato_attivita_id' => $sa->id
                                        ]),
                                    'attivita'),
                            'commesse')
                        ->create();
                }
            }
        }

    }
}
