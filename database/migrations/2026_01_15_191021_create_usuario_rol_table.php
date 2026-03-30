<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_rol', function (Blueprint $table) {
            $table->uuid('id_usuario');
            $table->unsignedBigInteger('id_rol');

            $table->primary(['id_usuario','id_rol']);
            $table->foreign('id_usuario')->references('id')->on('usuario');
            $table->foreign('id_rol')->references('id')->on('rol');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario_rol');
    }
};
