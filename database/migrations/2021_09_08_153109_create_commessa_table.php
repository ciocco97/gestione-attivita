<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommessaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commessa', function (Blueprint $table) {
            $table->id();
            $table->string('descrizione_commessa');
            $table->foreignId('cliente_id')->constrained('cliente');
            $table->foreignId('stato_commessa_id')->constrained('stato_commessa');
            $table->foreignId('persona_id')->constrained('persona');
            $table->unsignedTinyInteger('rapportino_commessa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commessa');
    }
}
