<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEmisionImpuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_emision_impuesto', function (Blueprint $table) {
            $table->integer('id_emision')->unsigned();
            $table->integer('id_impuesto')->unsigned();
            $table->foreign('id_emision')->references('id')->on('t_emision');
            $table->foreign('id_impuesto')->references('id')->on('t_impuesto');
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
        Schema::dropIfExists('t_emision_impuesto');
    }
}
