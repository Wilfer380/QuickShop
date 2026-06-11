<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('color');
            $table->string('ubicacion', 120)->nullable()->after('imagen');
            $table->decimal('precio_compra', 12, 2)->nullable()->after('kilometraje');
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'ubicacion', 'precio_compra']);
        });
    }
};
