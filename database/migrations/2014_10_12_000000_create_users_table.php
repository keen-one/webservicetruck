<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductor', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('apodo')->nullable(false);
            $table->string('correo')->nullable(false)->unique();
            $table->string('password')->nullable(false);
            $table->string('passwordChat')->nullable(false);
            $table->string("nombreUsuario")->nullable(false)->unique();
            $table->string("fechaNac")->nullable(false);
            $table->string("latitud")->nullable(true)->default("");
            $table->string("longitud")->nullable(true)->default("");
            $table->string("auth_token")->nullable(false)->unique();
        });
        Schema::create('apoderado', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('apodo')->nullable(false);
            $table->string('correo')->nullable(false)->unique();
            $table->string('password')->nullable(false);
            $table->string("passwordChat")->nullable(false);
            $table->string("nombreUsuario")->nullable(false)->unique();
            $table->string("fechaNac")->nullable(false);
            $table->string("latitudInicial")->nullable(true)->default("");
            $table->string("longitudInicial")->nullable(true)->default("");
            $table->string("latitudFinal")->nullable(true)->default("");
            $table->string("longitudFinal")->nullable(true)->default("");
            $table->string("lugar")->nullable(true)->default("");
            $table->string("auth_token")->nullable(false)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apoderado');
        Schema::dropIfExists("conductor");
    }
}
