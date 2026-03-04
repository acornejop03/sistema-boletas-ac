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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('codigo', 20)->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('nivel', ['BASICO', 'INTERMEDIO', 'AVANZADO'])->default('BASICO');
            $table->integer('duracion_meses')->default(1);
            $table->integer('duracion_horas')->nullable();
            $table->decimal('precio_matricula', 10, 2)->default(0);
            $table->decimal('precio_pension', 10, 2);
            $table->decimal('precio_materiales', 10, 2)->default(0);
            $table->boolean('afecto_igv')->default(false);
            $table->string('tipo_afectacion_igv', 2)->default('20');
            $table->integer('max_alumnos')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
