<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEmisionConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * No tiene sentido esta tabla debido a que es informativo el concepto
         */
        Schema::create('t_emision_concepto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->nullable();
            $table->integer('cantidad');
            $table->string('nombre');
            $table->float('precio_unitario', 10, 2);
            $table->string('identificacion')->nullable();
            $table->string('predial')->nullable();
            $table->string('id_concepto')->nullable();        
            $table->integer('id_unidad')->unsigned();
            $table->integer('id_emision')->unsigned();
            
            $table->integer('id_contribuyente')->unsigned();
            
            $table->foreign('id_unidad')->references('id')->on('c_unidad_prod');
            $table->foreign('id_emision')->references('id')->on('t_emision');
            $table->foreign('id_contribuyente')->references('id')->on('c_contribuyente'); 
            //$table->foreign('id_concepto')->references('id')->on('c_concepto');
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
        Schema::dropIfExists('t_emision_concepto');
    }
}
