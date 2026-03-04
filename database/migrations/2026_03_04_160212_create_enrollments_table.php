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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('periodo', 7)->comment('Ej: 2024-01');
            $table->date('fecha_matricula');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('turno', ['MAÑANA', 'TARDE', 'NOCHE'])->default('MAÑANA');
            $table->enum('estado', ['ACTIVO', 'CULMINADO', 'RETIRADO', 'SUSPENDIDO'])->default('ACTIVO');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['student_id', 'course_id', 'periodo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
