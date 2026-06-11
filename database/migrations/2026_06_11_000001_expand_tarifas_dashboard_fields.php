<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarifas', function (Blueprint $table): void {
            if (! Schema::hasColumn('tarifas', 'icono')) {
                $table->string('icono', 50)->nullable()->after('tipo_vehiculo');
            }

            if (! Schema::hasColumn('tarifas', 'tarifa_minuto')) {
                $table->decimal('tarifa_minuto', 12, 2)->nullable()->after('valor');
            }

            if (! Schema::hasColumn('tarifas', 'tarifa_hora')) {
                $table->decimal('tarifa_hora', 12, 2)->nullable()->after('tarifa_minuto');
            }

            if (! Schema::hasColumn('tarifas', 'tarifa_dia')) {
                $table->decimal('tarifa_dia', 12, 2)->nullable()->after('tarifa_hora');
            }

            if (! Schema::hasColumn('tarifas', 'tarifa_noche')) {
                $table->decimal('tarifa_noche', 12, 2)->nullable()->after('tarifa_dia');
            }

            if (! Schema::hasColumn('tarifas', 'zona')) {
                $table->string('zona', 50)->nullable()->index()->after('tipo_cobro');
            }

            if (! Schema::hasColumn('tarifas', 'estado')) {
                $table->string('estado', 20)->default('activa')->index()->after('activa');
            }

            if (! Schema::hasColumn('tarifas', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('descripcion');
            }
        });

        $rows = DB::table('tarifas')->get();

        foreach ($rows as $row) {
            $base = (float) ($row->tarifa_hora ?? $row->valor ?? 0);

            DB::table('tarifas')
                ->where('id', $row->id)
                ->update([
                    'tarifa_minuto' => $row->tarifa_minuto ?? round($base / 60),
                    'tarifa_hora' => $row->tarifa_hora ?? $base,
                    'tarifa_dia' => $row->tarifa_dia ?? ($base * 6),
                    'tarifa_noche' => $row->tarifa_noche ?? ($base * 3),
                    'estado' => $row->estado ?? ((bool) $row->activa ? 'activa' : 'inactiva'),
                    'icono' => $row->icono ?? match ((string) $row->tipo_vehiculo) {
                        'moto', 'motocicleta' => 'moto',
                        'camioneta' => 'camioneta',
                        'camion' => 'camion',
                        'bicicleta' => 'bicicleta',
                        default => 'carro',
                    },
                    'zona' => $row->zona ?? null,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('tarifas', function (Blueprint $table): void {
            $columns = ['icono', 'tarifa_minuto', 'tarifa_hora', 'tarifa_dia', 'tarifa_noche', 'zona', 'estado', 'observaciones'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tarifas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
