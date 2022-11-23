<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCProductoServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_producto_servicio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('id_sat');
            $table->string('tipo')->nullable();
            $table->string('catalogo')->nullable();
            $table->string('sub_catalogo')->nullable();
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
        Schema::dropIfExists('c_producto_servicio');
    }
}
