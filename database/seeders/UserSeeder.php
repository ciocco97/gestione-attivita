<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;

class UserSeeder extends Seeder
{
    /**
     * Run the database user seeds.
     *
     * @return void
     */
    public function run()
    {
        Persona::create([
            'nome' => 'Paolo',
            'cognome' => 'Cremascoli',
            'email' => 'p.cremascoli@gmail.com',
            'password' => md5('password')
        ]);

        Persona::create([
            'nome' => 'Gianmarco',
            'cognome' => 'Baronio',
            'email' => 'g.baronio@gmail.com',
            'password' => md5('password')
        ]);

        Persona::create([
            'nome' => 'Alice',
            'cognome' => 'Cremascoli',
            'email' => 'a.cremascoli@gmail.com',
            'password' => md5('password')
        ]);

        Persona::create([
            'nome' => 'Andrea',
            'cognome' => 'Malago',
            'email' => 'a.malago@gmail.com',
            'password' => md5('password')
        ]);

        Persona::create([
            'nome' => 'Riccardo',
            'cognome' => 'Tengattini',
            'email' => 'a.tengattini@gmail.com',
            'password' => md5('password')
        ]);
    }
}
