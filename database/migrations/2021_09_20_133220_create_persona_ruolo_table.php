<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaRuoloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_ruolo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('persona');
            $table->foreignId('ruolo_id')->constrained('ruolo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_ruolo');
    }
}
