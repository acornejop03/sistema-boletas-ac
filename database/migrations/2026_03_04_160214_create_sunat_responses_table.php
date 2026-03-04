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
        Schema::create('sunat_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->string('accion');
            $table->string('endpoint_url');
            $table->text('xml_enviado')->nullable();
            $table->integer('codigo_respuesta')->nullable();
            $table->text('descripcion_respuesta')->nullable();
            $table->boolean('exitoso')->default(false);
            $table->integer('intento')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sunat_responses');
    }
};
