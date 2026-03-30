<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('datos_consultas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('email', 255);
            $table->string('telefono', 15);
            $table->text('consulta');
            $table->string('estado', 50)->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('datos_consultas');
    }
};