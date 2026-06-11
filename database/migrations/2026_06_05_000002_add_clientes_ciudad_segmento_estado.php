<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('ciudad', 120)->nullable()->after('direccion');
            $table->string('segmento', 40)->default('activo')->after('ciudad');
            $table->string('estado', 20)->default('activo')->index()->after('segmento');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['ciudad', 'segmento', 'estado']);
        });
    }
};
