<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerSottopostoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_sottoposto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('persona');
            $table->foreignId('sottoposto_id')->constrained('persona');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_sottoposto');
    }
}
