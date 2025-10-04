<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            // Eliminar la vieja constraint
            $table->dropForeign('venta_detalles_catalogo_id_foreign');

            // Crear la nueva FK hacia productos
            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);

            $table->foreign('producto_id')
                  ->references('id')
                  ->on('catalogos')
                  ->onDelete('cascade');
        });
    }
};
