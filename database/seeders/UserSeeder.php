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
            'cognome' => '',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password')
        ]);

        $paperina = Persona::create([
            'nome' => 'Paperina',
            'cognome' => 'Daisy Duck',
            'email' => 'p.daisyduck@email.pa',
            'password' => md5('password')
        ]);

        $paperino = Persona::create([
            'nome' => 'Paperino',
            'cognome' => 'Donald Duck',
            'email' => 'p.donaldduck@email.pa',
            'password' => md5('password')
        ]);

        $pippo = Persona::create([
            'nome' => 'Pippo',
            'cognome' => 'Goofy',
            'email' => 'p.goofy@email.to',
            'password' => md5('password'),
        ]);

        $topolino = Persona::create([
            'nome' => 'Topolino',
            'cognome' => 'Mickey Mouse',
            'email' => 't.mickeymouse@email.to',
            'password' => md5('password'),
        ]);

        $gamba_di_legno = Persona::create([
            'nome' => 'Gamba di Legno',
            'cognome' => 'Pete',
            'email' => 'g.pete@email.to',
            'password' => md5('password'),
        ]);

        DB::table('persona_ruolo')->insert([
            ['persona_id' => $paperina->id, 'ruolo_id' => 1],
            ['persona_id' => $paperina->id, 'ruolo_id' => 2],
            ['persona_id' => $paperino->id, 'ruolo_id' => 1],
            ['persona_id' => $paperino->id, 'ruolo_id' => 2],
            ['persona_id' => $pippo->id, 'ruolo_id' => 1],
            ['persona_id' => $admin->id, 'ruolo_id' => 4],
        ]);

        DB::table('manager_sottoposto')->insert([
            ['manager_id' => $paperina->id, 'sottoposto_id' => $paperina->id],
            ['manager_id' => $paperina->id, 'sottoposto_id' => $topolino->id],
            ['manager_id' => $paperina->id, 'sottoposto_id' => $pippo->id],
            ['manager_id' => $paperino->id, 'sottoposto_id' => $paperino->id],
            ['manager_id' => $paperino->id, 'sottoposto_id' => $topolino->id],
            ['manager_id' => $topolino->id, 'sottoposto_id' => $gamba_di_legno->id],
        ]);
    }
}
