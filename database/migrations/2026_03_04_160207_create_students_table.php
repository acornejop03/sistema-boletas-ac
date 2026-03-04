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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->tinyInteger('tipo_documento')->default(1)->comment('1=DNI, 6=RUC, 0=Sin doc');
            $table->string('numero_documento', 20)->nullable();
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_apoderado')->nullable();
            $table->string('nombre_apoderado')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ubigeo', 6)->nullable();
            $table->string('foto_path')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
