<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreateAttivitaInfoView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("CREATE VIEW attivita_info AS
                                SELECT
                                    attivita.id,
                                    attivita.descrizione_attivita,
                                    attivita.data,
                                    cliente.nome AS nome_cliente,
                                    commessa.descrizione_commessa,
                                    attivita.ora_inizio,
                                    attivita.ora_fine,
                                    attivita.durata,
                                    stato_attivita.descrizione_stato_attivita,
                                    attivita.rapportino_attivita,
                                    cliente.rapportino_cliente,
                                    commessa.rapportino_commessa,
                                    stato_commessa.id AS stato_commessa_id,
                                    stato_commessa.descrizione_stato_commessa,
                                    attivita.persona_id
                                FROM
                                    attivita, commessa, cliente, stato_attivita, stato_commessa
                                WHERE
                                    attivita.commessa_id=commessa.id AND
                                    commessa.cliente_id=cliente.id AND
                                    attivita.stato_attivita_id=stato_attivita.id AND
                                    commessa.stato_commessa_id=stato_commessa.id
                                ORDER BY data");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS attivita_info");
    }
}
