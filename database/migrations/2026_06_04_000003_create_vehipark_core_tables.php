<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20)->default('CC');
            $table->string('documento', 40)->unique();
            $table->string('nombres');
            $table->string('apellidos')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email')->nullable()->index();
            $table->string('direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('placa', 20)->nullable()->unique();
            $table->string('tipo', 30)->index();
            $table->string('marca');
            $table->string('modelo');
            $table->year('anio')->nullable();
            $table->string('color')->nullable();
            $table->string('vin')->nullable()->unique();
            $table->unsignedInteger('kilometraje')->nullable();
            $table->decimal('precio_venta', 12, 2)->nullable();
            $table->string('estado')->default('disponible')->index();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

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

        Schema::create('cupos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('zona')->nullable()->index();
            $table->string('tipo_vehiculo', 30)->index();
            $table->string('estado')->default('disponible')->index();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('tipo_vehiculo', 30)->index();
            $table->string('tipo_cobro', 30)->default('hora')->index();
            $table->decimal('valor', 10, 2);
            $table->boolean('activa')->default(true)->index();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

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
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('movimientos_parqueadero');
        Schema::dropIfExists('tarifas');
        Schema::dropIfExists('cupos');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('clientes');
    }
};
