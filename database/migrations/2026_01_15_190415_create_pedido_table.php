<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('codigo')->unique();
            $table->uuid('id_usuario');
            $table->enum('estado', ['creado','procesado','reparto','entregado','cancelado','devuelto']);
            $table->boolean('suscripcion')->default(false);
            $table->decimal('precio_total', 8, 2);
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuario');
        });

    } 
    
    public function down()
    {
        Schema::dropIfExists('pedido');
    }
};