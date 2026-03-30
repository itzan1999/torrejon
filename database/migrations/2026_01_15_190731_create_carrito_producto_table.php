<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrito');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad')->default(1);

            $table->primary(['id_carrito','id_producto']);
            $table->foreign('id_carrito')->references('id')->on('carrito');
            $table->foreign('id_producto')->references('id')->on('producto');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('carrito_producto');
    }
};