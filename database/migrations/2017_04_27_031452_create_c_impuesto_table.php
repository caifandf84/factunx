<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCImpuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_impuesto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 150);
            $table->string('id_sat', 50);
            $table->integer('id_tipo_impuesto')->unsigned()->nullable();;
            $table->foreign('id_tipo_impuesto')->references('id')->on('c_tipo_impuesto');
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
        Schema::dropIfExists('c_impuesto');
    }
}
