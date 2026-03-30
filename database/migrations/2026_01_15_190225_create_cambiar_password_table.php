<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cambiar_password', function (Blueprint $table) {
            $table->uuid('id_user')->primary();
            $table->string('token');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_expiracion')->nullable();
            $table->boolean('usado')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('usuario')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cambiar_password');
    }
};
