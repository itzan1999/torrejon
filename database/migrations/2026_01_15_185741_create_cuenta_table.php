<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuenta', function (Blueprint $table) {
            $table->uuid('id_user')->primary();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('password');
            $table->boolean('activa')->default(false);
            $table->timestamp('fecha_alta')->useCurrent();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('usuario');
        });

    }

    public function down()
    {
        Schema::dropIfExists('cuenta');
    }

};