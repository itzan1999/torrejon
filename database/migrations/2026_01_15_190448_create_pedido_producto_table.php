<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->uuid('id_pedido');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad')->default(1);

            $table->primary(['id_pedido','id_producto']);
            $table->foreign('id_pedido')->references('id')->on('pedido');
            $table->foreign('id_producto')->references('id')->on('producto');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_producto');
    }
};
