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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('ubigeo', 6)->default('150101');
            $table->string('departamento')->default('LIMA');
            $table->string('provincia')->default('LIMA');
            $table->string('distrito')->default('LIMA');
            $table->string('direccion');
            $table->string('urbanizacion')->nullable();
            $table->string('pais', 2)->default('PE');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('serie_boleta', 4)->default('B001');
            $table->string('serie_factura', 4)->default('F001');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
