<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gasto_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->boolean('es_fijo')->default(false); // p.ej. alquiler/luz
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gasto_categorias');
    }
};
