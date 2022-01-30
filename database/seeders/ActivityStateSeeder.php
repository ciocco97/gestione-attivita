<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatoAttivita;

class ActivityStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatoAttivita::create([
            'id' => 1,
            'descrizione_stato_attivita' => 'completata'
        ]);

        StatoAttivita::create([
            'id' => 2,
            'descrizione_stato_attivita' => 'aperta'
        ]);

        StatoAttivita::create([
            'id' => 3,
            'descrizione_stato_attivita' => 'annullata'
        ]);

        StatoAttivita::create([
            'id' => 4,
            'descrizione_stato_attivita' => 'approvata'
        ]);
    }
}
