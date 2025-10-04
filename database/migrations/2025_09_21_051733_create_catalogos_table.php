<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('catalogo', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion');
        $table->enum('tipo_item', ['servicio','producto']);
        $table->decimal('precio_base', 10,2);
        $table->enum('tipo_pago', ['unico','mensual']);
        $table->integer('plazo_entrega')->nullable();
        $table->string('categoria', 100)->nullable();
        $table->enum('estado', ['activo','inactivo'])->default('activo');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogos');
    }
};
