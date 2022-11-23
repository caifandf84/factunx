<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_concepto', function (Blueprint $table) {
            $table->string('id')->primary();
            //$table->string('codigo')->nullable();
            $table->string('nombre');
            $table->float('precio_unitario', 10, 2);
            $table->string('identificacion')->nullable();
            $table->string('predial')->nullable();
            $table->string('id_producto_servicio')->nullable();
            $table->integer('codigo_barra');
            $table->integer('id_unidad')->unsigned();
            $table->integer('id_contribuyente')->unsigned();
            $table->foreign('id_unidad')->references('id')->on('c_unidad_prod');
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
        Schema::dropIfExists('t_concepto');
    }
}
