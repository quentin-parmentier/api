<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('pseudo')->index()->unique();
            $table->string('sexe')->nullable();
            $table->string('mail')->unique();
            $table->date('birthday')->nullable();
            $table->integer('level');
            $table->string('mdp');
            $table->string('telephone')->nullable();
            $table->string('country')->nullable();
            $table->string('description')->nullable();
            $table->string('mdp');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
