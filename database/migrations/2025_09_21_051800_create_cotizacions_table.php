<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->date('fecha');
            $table->enum('estado', ['en espera', 'enviada', 'aceptada', 'rechazada'])->default('en espera');
            $table->decimal('total_sin_igv', 10, 2)->default(0);
            $table->boolean('igv_incluido')->default(false);
            $table->decimal('igv_monto', 10, 2)->default(0);
            $table->decimal('total_con_igv', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
