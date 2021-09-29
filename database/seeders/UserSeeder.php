<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database user seeds.
     *
     * @return void
     */
    public function run()
    {
        $francesco = Persona::create([
            'nome' => 'Francesco',
            'cognome' => 'Cremascoli',
            'email' => 'francesco.cremascoli97@gmail.com',
            'password' => md5('password')
        ]);

        $paolo = Persona::create([
            'nome' => 'Paolo',
            'cognome' => 'Cremascoli',
            'email' => 'p.cremascoli@gmail.com',
            'password' => md5('password')
        ]);

        $gianmarco = Persona::create([
            'nome' => 'Gianmarco',
            'cognome' => 'Baronio',
            'email' => 'g.baronio@gmail.com',
            'password' => md5('password')
        ]);

        $alice = Persona::create([
            'nome' => 'Alice',
            'cognome' => 'Cremascoli',
            'email' => 'a.cremascoli@gmail.com',
            'password' => md5('password'),
        ]);

        $andrea = Persona::create([
            'nome' => 'Andrea',
            'cognome' => 'Malago',
            'email' => 'a.malago@gmail.com',
            'password' => md5('password'),
        ]);

        $riccardo = Persona::create([
            'nome' => 'Riccardo',
            'cognome' => 'Tengattini',
            'email' => 'a.tengattini@gmail.com',
            'password' => md5('password'),
        ]);

        DB::table('persona_ruolo')->insert([
            ['persona_id' => $paolo->id, 'ruolo_id' => 1],
            ['persona_id' => $paolo->id, 'ruolo_id' => 2],
            ['persona_id' => $gianmarco->id, 'ruolo_id' => 1],
            ['persona_id' => $gianmarco->id, 'ruolo_id' => 2],
            ['persona_id' => $alice->id, 'ruolo_id' => 1]
        ]);

        DB::table('manager_sottoposto')->insert([
            ['manager_id' => $paolo->id, 'sottoposto_id' => $paolo->id],
            ['manager_id' => $paolo->id, 'sottoposto_id' => $andrea->id],
            ['manager_id' => $paolo->id, 'sottoposto_id' => $alice->id],
            ['manager_id' => $gianmarco->id, 'sottoposto_id' => $gianmarco->id],
            ['manager_id' => $gianmarco->id, 'sottoposto_id' => $andrea->id],
            ['manager_id' => $andrea->id, 'sottoposto_id' => $riccardo->id],
        ]);
    }
}
