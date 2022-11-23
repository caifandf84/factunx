<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContribuyenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('c_contribuyente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rfc');
            $table->string('razon_social');
            $table->string('calle')->nullable();
            $table->string('num_ext')->nullable();
            $table->string('num_int')->nullable();
            $table->string('localidad')->nullable();
            $table->integer('codigo_postal')->nullable();
            $table->integer('id_codigo_postal')->nullable();
            $table->string('colonia')->nullable();
            $table->integer('id_colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->integer('id_municipio')->nullable();
            $table->string('estado')->nullable();
            $table->integer('id_estado')->nullable();
            $table->string('pais')->nullable();
            $table->integer('id_pais')->nullable();
            $table->string('tipo_persona',1)->nullable();
            $table->string('regimen_fiscal')->nullable();
            $table->integer('id_regimen_fiscal')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('celular')->nullable();
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
        Schema::dropIfExists('c_contribuyente');
    }
}
