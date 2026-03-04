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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->integer('orden')->default(1);
            $table->text('descripcion');
            $table->string('unidad_medida', 10)->default('ZZ')->comment('ZZ=Servicios educativos');
            $table->decimal('cantidad', 10, 4)->default(1);
            $table->decimal('valor_unitario', 12, 4);
            $table->decimal('precio_unitario', 12, 4);
            $table->decimal('mto_valor_venta', 12, 4);
            $table->decimal('mto_base_igv', 12, 4)->default(0);
            $table->decimal('porcentaje_igv', 5, 2)->default(0);
            $table->decimal('igv', 12, 4)->default(0);
            $table->string('tipo_afectacion_igv', 2)->default('20');
            $table->decimal('total', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
