<?php

namespace Database\Seeders;

use App\Models\StatoFatturazione;
use Illuminate\Database\Seeder;

class BillingStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatoFatturazione::create([
            'id' => 1,
            'descrizione_stato_fatturazione' => 'da fatturare'
        ]);

        StatoFatturazione::create([
            'id' => 2,
            'descrizione_stato_fatturazione' => 'a contratto'
        ]);

        StatoFatturazione::create([
            'id' => 3,
            'descrizione_stato_fatturazione' => 'non fatturabile'
        ]);
    }
}
