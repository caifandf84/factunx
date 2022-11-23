<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTUsuarioContribuyenteEmisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_usuario_contribuyente_emisor', function (Blueprint $table) {
            //
            $table->integer('id_archivo_cer')->unsigned()->nullable();
            $table->integer('id_archivo_key')->unsigned()->nullable();
            
            $table->foreign('id_archivo_cer')->references('id')->on('t_archivo');
            $table->foreign('id_archivo_key')->references('id')->on('t_archivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_usuario_contribuyente_emisor', function (Blueprint $table) {
            //
        });
    }
}
