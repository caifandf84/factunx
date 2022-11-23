<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCUnidadProdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_unidad_prod', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 150);
            $table->string('simbolo', 20)->nullable();
            $table->string('id_sat', 20);
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
        Schema::dropIfExists('c_unidad_prod');
    }
}
