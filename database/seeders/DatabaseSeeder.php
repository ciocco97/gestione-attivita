<?php

namespace Database\Seeders;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Commessa;
use App\Models\Persona;
use App\Models\Ruolo;
use App\Models\StatoAttivita;
use App\Models\StatoCommessa;
use App\Models\StatoFatturazione;
use Carbon\Carbon;
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
        // RUOLO

        $administrator = Ruolo::create([
            'id' => 1,
            'descrizione_ruolo' => 'amministrativo'
        ]);

        $commercial = Ruolo::create([
            'id' => 2,
            'descrizione_ruolo' => 'commerciale'
        ]);

        $manager = Ruolo::create([
            'id' => 3,
            'descrizione_ruolo' => 'manager'
        ]);

        $amministratore = Ruolo::create([
            'id' => 4,
            'descrizione_ruolo' => 'amministratore'
        ]);

        // PERSONA

        $admin = Persona::create([
            'nome' => 'Admin',
            'cognome' => '',
            'email' => 'team.biteam.srl@gmail.com',
            'password' => md5('password')
        ]);

        $luigi = Persona::create([
            'nome' => 'Luigi',
            'cognome' => 'Romoli',
            'email' => 'l.romoli@gmail.com',
            'password' => md5('ETZZAM1biyp*')
        ]);

        $franco = Persona::create([
            'nome' => 'Franco',
            'cognome' => 'Malgaro',
            'email' => 'f.malgaro@gmail.com',
            'password' => md5('password')
        ]);

        $alessia = Persona::create([
            'nome' => 'Alessia',
            'cognome' => 'Zidi',
            'email' => 'a.zidi@gmail.com',
            'password' => md5('ePaEpE@nEY&!'),
        ]);

        $giancarlo = Persona::create([
            'nome' => 'Giancarlo',
            'cognome' => 'Pedroni',
            'email' => 'g.pedroni@gmail.com',
            'password' => md5('$JEbkHg3tQoU'),
        ]);

        $fausta = Persona::create([
            'nome' => 'Fausta',
            'cognome' => 'Rossi',
            'email' => 'f.rossi@gmail.com',
            'password' => md5('y&D#RW!ggSZT'),
        ]);

        $pietro = Persona::create([
            'nome' => 'Pietro',
            'cognome' => 'Leali',
            'email' => 'p.leali@gmail.com',
            'password' => md5('password'),
        ]);

        // RUOLI E TEAMS

        DB::table('persona_ruolo')->insert([
            ['persona_id' => $luigi->id, 'ruolo_id' => 4],
            ['persona_id' => $admin->id, 'ruolo_id' => 4],
        ]);

        DB::table('manager_sottoposto')->insert([
            ['manager_id' => $fausta->id, 'sottoposto_id' => $alessia->id],
        ]);

        // STATO_COMMESSA

        $open = StatoCommessa::create([
            'id' => 1,
            'descrizione_stato_commessa' => 'aperta'
        ]);

        $closed = StatoCommessa::create([
            'id' => 2,
            'descrizione_stato_commessa' => 'chiusa'
        ]);

        // STATO FATTURAZIONE

        $billable = StatoFatturazione::create([
            'id' => 1,
            'descrizione_stato_fatturazione' => 'da fatturare'
        ]);

        $contract = StatoFatturazione::create([
            'id' => 2,
            'descrizione_stato_fatturazione' => 'a contratto'
        ]);

        $not_billable = StatoFatturazione::create([
            'id' => 3,
            'descrizione_stato_fatturazione' => 'non fatturabile'
        ]);

        // STATO ATTIVITA


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

        // CLIENTI

        $costumer0 = Cliente::create([
            'nome' => '# Altro cliente',
            'email' => 'team.biteam.srl@gmail.com',
            'rapportino_cliente' => 0
        ]);

        $elisium = Cliente::create([
            'nome' => 'Elisium',
            'email' => 'elisium@gmail.com',
            'rapportino_cliente' => 0
        ]);

        $fratelli = Cliente::create([
            'nome' => 'Fratelli R srl',
            'email' => 'fratelli@gmail.com.com',
            'rapportino_cliente' => 1
        ]);

        for ($i = 0; $i < 10; $i++) {
            Cliente::factory()->create();
        }

        // COMMESSE

        $order0 = Commessa::create([
            'descrizione_commessa' => '# Altra commessa',
            'cliente_id' => $costumer0->id,
            'stato_commessa_id' => $open->id,
            'persona_id' => $admin->id,
            'rapportino_commessa' => 0
        ]);

        $sviluppoEzSQL = Commessa::create([
            'descrizione_commessa' => 'Sviluppo EasySQL',
            'cliente_id' => $elisium->id,
            'stato_commessa_id' => 1,
            'persona_id' => $admin->id,
            'rapportino_commessa' => 0
        ]);

        $commessa_alessia = Commessa::create([
            'descrizione_commessa' => 'Installazione impianti di rete',
            'cliente_id' => $fratelli->id,
            'stato_commessa_id' => 1,
            'persona_id' => $admin->id,
            'rapportino_commessa' => 0
        ]);

        $stati_fatturazione = StatoFatturazione::all();
        $persone = Persona::all();
        $clienti = Cliente::all();


        for ($i = 0; $i < 30; $i++) {
            $c = Cliente::find(rand(1, $clienti->count()));
            Commessa::factory()
                ->count(1)
                ->state([
                    'cliente_id' => $c->id,
                    'persona_id' => 1,
                    'stato_commessa_id' => 1
                ])->create();
        }

        $commesse = Commessa::all();

        for ($i = 1; $i <= 10; $i++) {
            foreach ($stati_fatturazione as $sf) {
                foreach ($persone as $p) {
                    $co = Commessa::find(rand(1, $commesse->count()));
                    Attivita::factory()
                        ->count(2)->state([
                            'persona_id' => $p->id,
                            'stato_fatturazione_id' => $sf->id,
                            'commessa_id' => $co->id
                        ])->create();
                }
            }
        }

        // ATTIVITà DI ALESSIA

        $activity_date = Carbon::now()->startOfMonth()->subDay()->subDays(12);

        $alessia_activity1 = Attivita::create([
            'persona_id' => $alessia->id,
            'stato_fatturazione_id' => $billable->id,
            'commessa_id' => $commessa_alessia->id,
            'data' => $activity_date->format('Y-m-d'),
            'ora_inizio' => $activity_date->format('H:i'),
            'ora_fine' => $activity_date->addMinutes(85)->format('H:i'),
            'durata' => '1:25',
            'luogo' => 'Ufficio',
            'descrizione_attivita' => 'Configurazione switch per data center L3 a 64 porte QSFP28 100Gb',
            'note_interne' => 'Lavoro svolto da remoto',
            'rapportino_attivita' => 0,
            'contabilizzata' => 1,
            'stato_attivita_id' => 2,
        ]);

        $activity_date = Carbon::now();

        $alessia_activity2 = Attivita::create([
            'persona_id' => $alessia->id,
            'stato_fatturazione_id' => $billable->id,
            'commessa_id' => $commessa_alessia->id,
            'data' => $activity_date->format('Y-m-d'),
            'ora_inizio' => $activity_date->format('H:i'),
            'ora_fine' => $activity_date->addMinutes(85)->format('H:i'),
            'durata' => '1:25',
            'luogo' => 'Ufficio',
            'descrizione_attivita' => 'Configurazione switch per data center L3 a 64 porte QSFP28 100Gb',
            'note_interne' => 'Lavoro svolto da remoto',
            'rapportino_attivita' => 0,
            'contabilizzata' => 0,
            'stato_attivita_id' => 2,
        ]);

    }
}
