<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatoCommessa;

class OrderStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatoCommessa::create([
            'id' => 1,
            'descrizione_stato_commessa' => 'aperta'
        ]);

        StatoCommessa::create([
            'id' => 2,
            'descrizione_stato_commessa' => 'annullata'
        ]);
    }
}
