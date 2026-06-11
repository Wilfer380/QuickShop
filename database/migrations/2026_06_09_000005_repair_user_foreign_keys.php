<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['sqlite', 'mysql'], true)) {
            $this->disableForeignKeys($driver);
        }

        $this->rebuildProducts();
        $this->rebuildRoleUser();
        $this->rebuildAuditorias();
        $this->rebuildVentas();
        $this->rebuildMovimientosParqueadero();
        $this->rebuildPagos();

        if (in_array($driver, ['sqlite', 'mysql'], true)) {
            $this->enableForeignKeys($driver);
        }
    }

    public function down(): void
    {
        // Intentionally left blank.
    }

    private function disableForeignKeys(string $driver): void
    {
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
    }

    private function enableForeignKeys(string $driver): void
    {
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function rebuildProducts(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        $this->rebuildTable('products', function (): void {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('stock');
                $table->timestamps();
            });
        }, [
            'id', 'user_id', 'category_id', 'name', 'description', 'price', 'stock', 'created_at', 'updated_at',
        ], []);
    }

    private function rebuildRoleUser(): void
    {
        if (! Schema::hasTable('role_user')) {
            return;
        }

        $this->rebuildTable('role_user', function (): void {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'role_id']);
            });
        }, [
            'id', 'user_id', 'role_id', 'created_at', 'updated_at',
        ], ['role_user_user_id_role_id_unique']);
    }

    private function rebuildAuditorias(): void
    {
        if (! Schema::hasTable('auditorias')) {
            return;
        }

        $this->rebuildTable('auditorias', function (): void {
            Schema::create('auditorias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('accion')->index();
                $table->string('auditable_type')->nullable();
                $table->unsignedBigInteger('auditable_id')->nullable();
                $table->json('datos_anteriores')->nullable();
                $table->json('datos_nuevos')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['auditable_type', 'auditable_id']);
            });
        }, [
            'id', 'user_id', 'accion', 'auditable_type', 'auditable_id', 'datos_anteriores', 'datos_nuevos', 'ip_address', 'user_agent', 'created_at', 'updated_at',
        ], ['auditorias_accion_index', 'auditorias_auditable_type_auditable_id_index']);
    }

    private function rebuildVentas(): void
    {
        if (! Schema::hasTable('ventas')) {
            return;
        }

        $this->rebuildTable('ventas', function (): void {
            Schema::create('ventas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
                $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
                $table->foreignId('vendedor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('fecha_venta');
                $table->decimal('precio_base', 12, 2);
                $table->decimal('descuento', 12, 2)->default(0);
                $table->decimal('impuestos', 12, 2)->default(0);
                $table->decimal('total', 12, 2);
                $table->string('estado')->default('pendiente')->index();
                $table->text('notas')->nullable();
                $table->timestamps();

                $table->unique('vehiculo_id');
            });
        }, [
            'id', 'cliente_id', 'vehiculo_id', 'vendedor_id', 'fecha_venta', 'precio_base', 'descuento', 'impuestos', 'total', 'estado', 'notas', 'created_at', 'updated_at',
        ], ['ventas_vehiculo_id_unique']);
    }

    private function rebuildMovimientosParqueadero(): void
    {
        if (! Schema::hasTable('movimientos_parqueadero')) {
            return;
        }

        $this->rebuildTable('movimientos_parqueadero', function (): void {
            Schema::create('movimientos_parqueadero', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
                $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
                $table->foreignId('cupo_id')->nullable()->constrained('cupos')->nullOnDelete();
                $table->foreignId('tarifa_id')->nullable()->constrained('tarifas')->nullOnDelete();
                $table->foreignId('registrado_por_id')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('entrada_at');
                $table->dateTime('salida_at')->nullable();
                $table->unsignedInteger('minutos')->nullable();
                $table->decimal('total', 10, 2)->nullable();
                $table->string('estado')->default('abierto')->index();
                $table->text('observaciones')->nullable();
                $table->timestamps();

                $table->index(['vehiculo_id', 'estado']);
            });
        }, [
            'id', 'vehiculo_id', 'cliente_id', 'cupo_id', 'tarifa_id', 'registrado_por_id', 'entrada_at', 'salida_at', 'minutos', 'total', 'estado', 'observaciones', 'created_at', 'updated_at',
        ], ['movimientos_parqueadero_vehiculo_id_estado_index', 'movimientos_parqueadero_estado_index']);
    }

    private function rebuildPagos(): void
    {
        if (! Schema::hasTable('pagos')) {
            return;
        }

        $this->rebuildTable('pagos', function (): void {
            Schema::create('pagos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
                $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
                $table->foreignId('movimiento_parqueadero_id')->nullable()->constrained('movimientos_parqueadero')->nullOnDelete();
                $table->foreignId('recibido_por_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('concepto')->index();
                $table->string('metodo_pago', 30);
                $table->decimal('valor', 12, 2);
                $table->dateTime('pagado_at')->nullable();
                $table->string('referencia')->nullable()->index();
                $table->string('estado')->default('registrado')->index();
                $table->text('notas')->nullable();
                $table->timestamps();
            });
        }, [
            'id', 'cliente_id', 'venta_id', 'movimiento_parqueadero_id', 'recibido_por_id', 'concepto', 'metodo_pago', 'valor', 'pagado_at', 'referencia', 'estado', 'notas', 'created_at', 'updated_at',
        ], ['pagos_concepto_index', 'pagos_referencia_index', 'pagos_estado_index']);
    }

    /**
     * @param array<int, string> $columns
     */
    private function rebuildTable(string $table, \Closure $create, array $columns, array $indexNames = []): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $temp = $table . '_backup';

        if (Schema::hasTable($temp)) {
            Schema::dropIfExists($temp);
        }

        if (DB::getDriverName() === 'sqlite') {
            foreach ($indexNames as $indexName) {
                DB::statement('DROP INDEX IF EXISTS ' . $indexName);
            }
        }

        DB::statement('CREATE TABLE ' . $temp . ' AS SELECT * FROM ' . $table);
        Schema::dropIfExists($table);
        $create();

        $columnList = implode(', ', array_map(static fn (string $column): string => '"' . $column . '"', $columns));
        DB::statement('INSERT INTO ' . $table . ' (' . $columnList . ') SELECT ' . $columnList . ' FROM ' . $temp);
        Schema::dropIfExists($temp);
    }
};
