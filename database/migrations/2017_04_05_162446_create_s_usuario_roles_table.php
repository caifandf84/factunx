<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSUsuarioRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_usuario_roles', function (Blueprint $table) {
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_roles')->unsigned();
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_roles')->references('id')->on('s_roles');
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
        Schema::dropIfExists('s_usuario_roles');
    }
}
