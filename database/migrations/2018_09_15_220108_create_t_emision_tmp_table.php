<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEmisionTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_emision_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_tipo_comprobante')->nullable();
            $table->string('serie')->nullable();
            $table->string('numero')->nullable();
            $table->float('total', 10, 2)->nullable();
            $table->integer('paso')->nullable();
            $table->text('proceso_emision')->nullable();
            $table->integer('id_contribuyente_emisor')->nullable()->unsigned();
            $table->integer('id_contribuyente_receptor')->nullable()->unsigned();
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
        Schema::dropIfExists('t_emision_tmp');
    }
}
