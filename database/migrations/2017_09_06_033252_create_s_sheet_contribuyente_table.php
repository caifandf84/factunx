<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSSheetContribuyenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_sheet_contribuyente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_sheet_google');
            $table->integer('id_contribuyente')->nullable()->unsigned();
            $table->boolean('asignado')->default(false)->nullable();
            $table->foreign('id_contribuyente')->references('id')->on('c_contribuyente');
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
        Schema::dropIfExists('s_sheet_contribuyente');
    }
}
