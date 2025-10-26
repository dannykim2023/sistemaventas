<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();

            // Relación opcional con proveedor (1 proveedor tiene muchos gastos)
            $table->foreignId('proveedor_id')
                ->nullable()
                ->constrained('proveedores')
                ->nullOnDelete();

            // Relación con categoría
            $table->foreignId('categoria_id')
                ->constrained('gasto_categorias')
                ->cascadeOnDelete();

            // Datos del gasto (moneda fija: soles)
            $table->string('descripcion');
            $table->decimal('monto_total', 12, 2); // siempre en PEN
            $table->enum('estado_pago', ['pendiente','pagado'])->default('pendiente');
            $table->enum('metodo_pago', ['transferencia','yape','plin','efectivo'])->default('efectivo');

            $table->date('fecha_emision');
            $table->date('fecha_pago')->nullable();

            // Solo imagen/captura como comprobante
            $table->string('comprobante_path')->nullable();

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices útiles
            $table->index(['fecha_emision', 'estado_pago']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
