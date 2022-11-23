<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('c_producto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->string('slug');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->unique();
            $table->integer('timbre')->nullable();
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
        Schema::dropIfExists('c_producto');
    }
}
