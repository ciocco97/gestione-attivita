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
            'descrizione_stato_attivita' => 'completata'
        ]);

        StatoAttivita::create([
            'descrizione_stato_attivita' => 'aperta'
        ]);

        StatoAttivita::create([
            'descrizione_stato_attivita' => 'annullata'
        ]);

        StatoAttivita::create([
            'descrizione_stato_attivita' => 'approvata'
        ]);
    }
}
