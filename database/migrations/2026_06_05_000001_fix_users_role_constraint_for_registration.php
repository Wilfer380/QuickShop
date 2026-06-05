<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $usersSql = (string) (DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'users'")->sql ?? '');

        if (str_contains($usersSql, '"role" varchar') && ! str_contains($usersSql, 'check ("role"')) {
            return;
        }

        if (Schema::hasTable('users_old')) {
            DB::statement(<<<SQL
                INSERT INTO users (
                    id, name, email, email_verified_at, password, role, money,
                    remember_token, created_at, updated_at, status, phone, documento
                )
                SELECT
                    id,
                    name,
                    email,
                    email_verified_at,
                    password,
                    CASE role
                        WHEN 'buyer' THEN 'empleado'
                        WHEN 'seller' THEN 'supervisor'
                        WHEN 'admin' THEN 'admin'
                        ELSE COALESCE(role, 'empleado')
                    END,
                    COALESCE(money, 0),
                    remember_token,
                    created_at,
                    updated_at,
                    COALESCE(status, 'active'),
                    phone,
                    documento
                FROM users_old
            SQL);

            DB::statement('DROP TABLE users_old');
            DB::statement("DELETE FROM sqlite_sequence WHERE name = 'users'");
            DB::statement("INSERT INTO sqlite_sequence (name, seq) SELECT 'users', COALESCE(MAX(id), 0) FROM users");
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');
        DB::statement('ALTER TABLE users RENAME TO users_old');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('empleado');
            $table->decimal('money', 10, 2)->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->string('status')->default('active')->index();
            $table->string('phone', 30)->nullable();
            $table->string('documento', 40)->nullable()->unique();
        });

        DB::statement(<<<SQL
            INSERT INTO users (
                id, name, email, email_verified_at, password, role, money,
                remember_token, created_at, updated_at, status, phone, documento
            )
            SELECT
                id,
                name,
                email,
                email_verified_at,
                password,
                CASE role
                    WHEN 'buyer' THEN 'empleado'
                    WHEN 'seller' THEN 'supervisor'
                    WHEN 'admin' THEN 'admin'
                    ELSE COALESCE(role, 'empleado')
                END,
                COALESCE(money, 0),
                remember_token,
                created_at,
                updated_at,
                COALESCE(status, 'active'),
                phone,
                documento
            FROM users_old
        SQL);

        DB::statement('DROP TABLE users_old');
        DB::statement("DELETE FROM sqlite_sequence WHERE name = 'users'");
        DB::statement("INSERT INTO sqlite_sequence (name, seq) SELECT 'users', COALESCE(MAX(id), 0) FROM users");
        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $usersSql = (string) (DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'users'")->sql ?? '');

        if (str_contains($usersSql, 'check ("role" in (\'buyer\', \'seller\', \'admin\'))')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');
        DB::statement('ALTER TABLE users RENAME TO users_new');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer');
            $table->decimal('money', 10, 2)->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->string('status')->default('active')->index();
            $table->string('phone', 30)->nullable();
            $table->string('documento', 40)->nullable()->unique();
        });

        DB::statement(<<<SQL
            INSERT INTO users (
                id, name, email, email_verified_at, password, role, money,
                remember_token, created_at, updated_at, status, phone, documento
            )
            SELECT
                id,
                name,
                email,
                email_verified_at,
                password,
                CASE role
                    WHEN 'empleado' THEN 'buyer'
                    WHEN 'supervisor' THEN 'seller'
                    WHEN 'admin' THEN 'admin'
                    ELSE 'buyer'
                END,
                COALESCE(money, 0),
                remember_token,
                created_at,
                updated_at,
                COALESCE(status, 'active'),
                phone,
                documento
            FROM users_new
        SQL);

        DB::statement('DROP TABLE users_new');
        DB::statement("DELETE FROM sqlite_sequence WHERE name = 'users'");
        DB::statement("INSERT INTO sqlite_sequence (name, seq) SELECT 'users', COALESCE(MAX(id), 0) FROM users");
        DB::statement('PRAGMA foreign_keys=ON');
    }
};
