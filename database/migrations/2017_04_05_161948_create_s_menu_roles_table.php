<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSMenuRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_menu_roles', function (Blueprint $table) {
            $table->integer('id_menu')->unsigned();
            $table->integer('id_roles')->unsigned();
            $table->foreign('id_roles')->references('id')->on('s_roles');
            $table->foreign('id_menu')->references('id')->on('s_menu');
            $table->boolean('crear');
            $table->boolean('leer');
            $table->boolean('actualizar');
            $table->boolean('borrar');
            $table->primary(array('id_roles', 'id_menu'));
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
        Schema::dropIfExists('s_menu_roles');
    }
}
