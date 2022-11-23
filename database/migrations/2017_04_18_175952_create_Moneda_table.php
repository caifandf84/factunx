<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonedaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('c_moneda', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nombre', 100);
            $table->integer('decimales', $autoIncrement = false, $unsigned = false);
            $table->double('porcentaje', 15, 8);
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
        //
        Schema::dropIfExists('c_moneda');
    }
}
