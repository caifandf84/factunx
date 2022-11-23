<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEmisionPedimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_emision_pedimento', function (Blueprint $table) {
            $table->integer('id_emision')->unsigned();
            $table->integer('id_pedimento')->unsigned();
            $table->foreign('id_emision')->references('id')->on('t_emision');
            $table->foreign('id_pedimento')->references('id')->on('t_pedimento');
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
        Schema::dropIfExists('t_emision_pedimento');
    }
}
