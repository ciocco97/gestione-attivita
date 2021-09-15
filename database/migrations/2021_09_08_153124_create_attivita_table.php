<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttivitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attivita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('persona');
            $table->foreignId('commessa_id')->constrained('commessa');
            $table->date('data');
            $table->time('ora_inizio')->nullable();
            $table->time('ora_fine')->nullable();
            $table->time('durata')->nullable();
            $table->string('luogo')->nullable();
            $table->string('descrizione_attivita');
            $table->text('note_interne')->nullable();
            $table->foreignId('stato_attivita_id')->constrained('stato_attivita');
            $table->unsignedTinyInteger('rapportino_attivita');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attivita');
    }
}
