<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCUsoCfdiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_uso_cfdi', function (Blueprint $table) {
            $table->string('id');
            $table->string('nombre', 100);
            $table->boolean('isFisica');
            $table->boolean('isMoral');
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
        Schema::dropIfExists('c_uso_cfdi');
    }
}
