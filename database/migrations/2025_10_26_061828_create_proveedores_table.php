<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    if (\Illuminate\Support\Facades\Schema::hasTable('proveedores')) {
        // La tabla ya existe; no la volvemos a crear.
        return;
    }

    Schema::create('proveedores', function (Blueprint $table) {
        $table->id();
        $table->enum('tipo', ['empresa', 'empleado', 'freelance']);
        $table->string('razon_social')->nullable();
        $table->string('nombre')->nullable();
        $table->enum('documento_tipo', ['RUC','DNI','CE'])->nullable();
        $table->string('documento_numero')->nullable();
        $table->string('telefono')->nullable();
        $table->string('email')->nullable();
        $table->enum('pago_preferido', ['transferencia','yape','plin','efectivo'])->default('efectivo');
        $table->string('cuenta')->nullable();
        $table->text('notas')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->index(['documento_tipo', 'documento_numero']);
    });
}

public function down(): void
{
    Schema::dropIfExists('proveedores');
}
};