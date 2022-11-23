<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCSerieNumeroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_serie_numero', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_contribuyente_padre')->unsigned();
            $table->string('serie');
            $table->integer('numero');
            $table->integer('id_img_empresa_1')->unsigned()->nullable();
            $table->integer('id_img_empresa_2')->unsigned()->nullable();
            $table->integer('id_img_empresa_3')->unsigned()->nullable();
            $table->foreign('id_contribuyente_padre')->references('id')->on('c_contribuyente');
            $table->foreign('id_img_empresa_1')->references('id')->on('t_archivo');
            $table->foreign('id_img_empresa_2')->references('id')->on('t_archivo');
            $table->foreign('id_img_empresa_3')->references('id')->on('t_archivo');
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
        Schema::dropIfExists('c_serie_numero');
    }
}
