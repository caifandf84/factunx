<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTContribuyenteProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_contribuyente_producto', function (Blueprint $table) {
            $table->integer('id_contribuyente_emisor')->unsigned();
            $table->integer('id_producto')->unsigned();
            $table->string('id_order');
            $table->string('tipo_de_pago');
            $table->string('referencia')->nullable();
            $table->string('estatus')->nullable();
            $table->foreign('id_contribuyente_emisor')->references('id')->on('c_contribuyente');
            $table->foreign('id_producto')->references('id')->on('c_producto');
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
        Schema::dropIfExists('t_contribuyente_producto');
    }
}
