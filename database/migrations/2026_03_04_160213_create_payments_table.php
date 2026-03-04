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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo_pago', ['MATRICULA', 'PENSION', 'MATERIALES', 'OTRO'])->default('PENSION');
            $table->string('periodo_pago', 7)->nullable()->comment('Ej: 2024-01');
            $table->text('descripcion_pago')->nullable();
            $table->date('fecha_pago');
            $table->enum('forma_pago', ['EFECTIVO', 'TARJETA', 'TRANSFERENCIA', 'YAPE', 'PLIN'])->default('EFECTIVO');
            $table->string('numero_operacion', 50)->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('igv', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('estado_pago', ['PENDIENTE', 'PAGADO', 'ANULADO'])->default('PAGADO');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
