<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimiento_saldo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->double('importe');
            $table->enum('tipo', ['recarga','pago','devolucion']);
            $table->string('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimiento_saldo');
    }
};