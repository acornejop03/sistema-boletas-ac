<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('login_intentos')->default(0)->after('ultimo_acceso');
            $table->timestamp('bloqueado_hasta')->nullable()->after('login_intentos');
            $table->string('ultimo_ip', 45)->nullable()->after('bloqueado_hasta');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_intentos', 'bloqueado_hasta', 'ultimo_ip']);
        });
    }
};
