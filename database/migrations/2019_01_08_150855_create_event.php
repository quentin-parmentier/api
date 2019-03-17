<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id_event');
            $table->string('sport');
            $table->date('date');
            $table->time('time');
            $table->string('description')->nullable();
            $table->string('titre');
            $table->integer('createur');
            $table->float('lat');
            $table->float('lng');
            $table->integer('nb_max');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
}
