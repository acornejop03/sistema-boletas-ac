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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipo_comprobante', 2)->default('03')->comment('01=Factura, 03=Boleta');
            $table->string('serie', 4);
            $table->integer('correlativo');
            $table->date('fecha_emision');
            $table->string('moneda', 3)->default('PEN');
            $table->decimal('mto_oper_gravadas', 12, 2)->default(0);
            $table->decimal('mto_oper_exoneradas', 12, 2)->default(0);
            $table->decimal('mto_oper_inafectas', 12, 2)->default(0);
            $table->decimal('mto_igv', 12, 2)->default(0);
            $table->decimal('valorventa', 12, 2)->default(0);
            $table->decimal('total_impuestos', 12, 2)->default(0);
            $table->decimal('mto_imp_venta', 12, 2)->default(0);
            $table->string('estado_sunat')->default('PENDIENTE');
            $table->text('sunat_descripcion')->nullable();
            $table->string('hash_cpe')->nullable();
            $table->string('nombre_xml')->nullable();
            $table->string('ruta_xml')->nullable();
            $table->string('ruta_cdr')->nullable();
            $table->string('ruta_pdf')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['company_id', 'tipo_comprobante', 'serie', 'correlativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
