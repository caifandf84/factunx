<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTImpuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_impuesto', function (Blueprint $table) {
            $table->increments('id');
            $table->float('tasa', 10, 2);
            $table->float('importe', 10, 2);
            $table->integer('id_impuesto')->unsigned();
            $table->foreign('id_impuesto')->references('id')->on('c_impuesto');
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
        Schema::dropIfExists('t_impuesto');
    }
}
