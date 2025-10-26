<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('gastos')) {
            Schema::create('gastos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
                $table->foreignId('categoria_id')->constrained('gasto_categorias')->cascadeOnDelete();
                $table->string('descripcion');
                $table->decimal('monto_total', 12, 2);
                $table->enum('estado_pago', ['pendiente','pagado'])->default('pendiente');
                $table->enum('metodo_pago', ['transferencia','yape','plin','efectivo'])->default('efectivo');
                $table->date('fecha_emision');
                $table->date('fecha_pago')->nullable();
                $table->string('comprobante_path')->nullable();
                $table->text('notas')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['fecha_emision', 'estado_pago']);
            });
        }
    }
    public function down(): void
    {
        // En prod, mejor no dropear automáticamente.
        // Schema::dropIfExists('gastos');
    }
};
