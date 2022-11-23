<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEmisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_emision', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serie')->nullable();
            $table->string('numero')->nullable();
            $table->dateTime('fecha_emision');
            $table->float('monto', 10, 2);
            $table->float('tipo_de_cambio', 10, 2);
            $table->string('condicion_de_pago')->nullable();
            $table->string('id_moneda');
            
            $table->string('id_metodo_de_pago');
            $table->string('id_tipo_comprobante');
            $table->string('id_uso_cfdi');
            $table->string('descuento')->nullable();
            $table->string('uuid')->nullable();
            $table->string('uuid_relacionado')->nullable();
            $table->string('id_tipo_relacion')->nullable();
            $table->string('estatus')->nullable();
            $table->float('desc_descuento', 10, 2)->nullable();
            
            $table->float('otro_importe_ret', 10, 2)->nullable();
            $table->float('importe_ret', 10, 2)->nullable();
            $table->float('otro_importe_tras', 10, 2)->nullable();
            $table->float('importe_tras', 10, 2)->nullable();
            $table->float('porcentaje_tasa_imp', 10, 2)->nullable();
            
            $table->float('subtotal', 10, 2);
            $table->float('total', 10, 2);
            $table->integer('id_tipo_documento')->unsigned()->nullable();
            $table->foreign('id_tipo_documento')->references('id')->on('c_tipo_documento');
            $table->integer('id_forma_de_pago')->unsigned()->nullable();
            $table->foreign('id_forma_de_pago')->references('id')->on('c_forma_de_pago');
            //$table->integer('id_metodo_de_pago')->unsigned()->nullable();
            //$table->foreign('id_metodo_de_pago')->references('id')->on('c_metodo_de_pago');
            $table->integer('id_contribuyente_emisor')->unsigned()->nullable();
            $table->foreign('id_contribuyente_emisor')->references('id')->on('c_contribuyente');
            $table->integer('id_contribuyente_receptor')->unsigned()->nullable();
            $table->foreign('id_contribuyente_receptor')->references('id')->on('c_contribuyente');
            $table->integer('id_archivo_xml')->unsigned()->nullable();
            $table->foreign('id_archivo_xml')->references('id')->on('t_archivo');
            $table->integer('id_archivo_pdf')->unsigned()->nullable();
            $table->foreign('id_archivo_pdf')->references('id')->on('t_archivo');
            $table->integer('id_archivo_qr')->unsigned()->nullable();
            $table->foreign('id_archivo_qr')->references('id')->on('t_archivo');
            $table->integer('id_archivo_cadena_original')->unsigned()->nullable();
            $table->foreign('id_archivo_cadena_original')->references('id')->on('t_archivo');
            
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
        Schema::dropIfExists('t_emision');
    }
}
