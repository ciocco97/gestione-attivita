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
        $admin = Persona::create([
            'nome' => 'Admin',
            'cognome' => 'Admin',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password')
        ]);

        $paolo = Persona::create([
            'nome' => 'Paolo',
            'cognome' => 'Cremascoli',
            'email' => 'pcremascoli@gmail.com',
            'password' => md5('password')
        ]);

        $gianmarco = Persona::create([
            'nome' => 'Gianmarco',
            'cognome' => 'Baronio',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password')
        ]);

        $alice = Persona::create([
            'nome' => 'Alice',
            'cognome' => 'Cremascoli',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password'),
        ]);

        $andrea = Persona::create([
            'nome' => 'Andrea',
            'cognome' => 'Malago',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password'),
        ]);

        $riccardo = Persona::create([
            'nome' => 'Riccardo',
            'cognome' => 'Tengattini',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password'),
        ]);

        DB::table('persona_ruolo')->insert([
            ['persona_id' => $paolo->id, 'ruolo_id' => 1],
            ['persona_id' => $paolo->id, 'ruolo_id' => 2],
            ['persona_id' => $gianmarco->id, 'ruolo_id' => 1],
            ['persona_id' => $gianmarco->id, 'ruolo_id' => 2],
            ['persona_id' => $alice->id, 'ruolo_id' => 1],
            ['persona_id' => $admin->id, 'ruolo_id' => 4],
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
