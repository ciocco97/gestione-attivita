<?php

namespace Database\Seeders;

use App\Models\Ruolo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = Ruolo::create([
            'id' => 1,
            'descrizione_ruolo' => 'amministratore'
        ]);

        $commercial = Ruolo::create([
            'id' => 2,
            'descrizione_ruolo' => 'commerciale'
        ]);

        $manager = Ruolo::create([
            'id' => 3,
            'descrizione_ruolo' => 'manager'
        ]);

    }
}
