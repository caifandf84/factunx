<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUsuarioContribuyeteEmisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_usuario_contribuyente_emisor', function (Blueprint $table) {
            $table->integer('id_users')->unsigned();
            $table->integer('id_contribuyente')->unsigned();
            $table->boolean('es_padre')->default(false);
            $table->bigInteger('timbres_contradados')->default(0);
            $table->bigInteger('timbres_gastados')->default(0);
            $table->bigInteger('timbres_restantes')->default(0);
            $table->string('contrasenia_sello')->nullable();
            $table->foreign('id_users')->references('id')->on('users');
            $table->foreign('id_contribuyente')->references('id')->on('c_contribuyente');
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
        Schema::dropIfExists('t_usuario_contribuyente_emisor');
    }
}
