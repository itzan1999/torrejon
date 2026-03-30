<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permiso', function (Blueprint $table) {
            $table->id();
            $table->string('desc_permiso');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permiso');
    }
};
