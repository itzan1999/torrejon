<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producto_suscripcion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto');
            $table->uuid('id_suscripcion');
            $table->integer('cantidad');

            $table->primary(['id_producto','id_suscripcion']);
            $table->foreign('id_producto')->references('id')->on('producto');
            $table->foreign('id_suscripcion')->references('id')->on('suscripcion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_suscripcion');
    }
};
