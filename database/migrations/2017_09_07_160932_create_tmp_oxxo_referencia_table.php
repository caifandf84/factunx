<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpOxxoReferenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_oxxo_referencia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_contribuyente_emisor');
            $table->integer('id_producto');
            $table->string('id_order');
            $table->string('referencia');         
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
        Schema::dropIfExists('tmp_oxxo_referencia');
    }
}
