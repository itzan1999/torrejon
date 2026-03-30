<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio', 8, 2);
            $table->integer('stock')->default(0);
            $table->text('descripcion')->nullable();
            $table->decimal('oferta', 5, 2)->default(0);
            $table->json('informacion_nutricional');
            $table->decimal('tamanyo', 6, 2)->nullable();
            $table->enum('unidad_medida', ['mg', 'g', 'kg', 'mL', 'L'])->nullable();
            $table->string('path_imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto');
    }
};