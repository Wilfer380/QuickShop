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

        if (DB::getDriverName() === 'sqlite') {
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

            $this->rebuildUsersTableForSqlite(
                sourceTable: 'users',
                backupTable: 'users_backup',
                roleMap: [
                    'buyer' => 'empleado',
                    'seller' => 'supervisor',
                    'admin' => 'admin',
                ],
            );

            return;
        }
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
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            $usersSql = (string) (DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'users'")->sql ?? '');

            if (str_contains($usersSql, 'check ("role" in (\'buyer\', \'seller\', \'admin\'))')) {
                return;
            }

            $this->rebuildUsersTableForSqlite(
                sourceTable: 'users',
                backupTable: 'users_new',
                roleMap: [
                    'empleado' => 'buyer',
                    'supervisor' => 'seller',
                    'admin' => 'admin',
                ],
                legacyRole: 'buyer',
            );

            return;
        }
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
    }

    /**
     * @param array<string, string> $roleMap
     */
    private function rebuildUsersTableForSqlite(
        string $sourceTable,
        string $backupTable,
        array $roleMap,
        string $legacyRole = 'empleado',
    ): void {
        DB::statement('PRAGMA foreign_keys=OFF');

        if (Schema::hasTable($backupTable)) {
            Schema::drop($backupTable);
        }

        DB::statement('CREATE TABLE ' . $backupTable . ' AS SELECT * FROM ' . $sourceTable);
        Schema::drop('users');

        Schema::create('users', function (Blueprint $table) use ($legacyRole) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default($legacyRole);
            $table->decimal('money', 10, 2)->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->string('status')->default('active')->index();
            $table->string('phone', 30)->nullable();
            $table->string('documento', 40)->nullable()->unique();
        });

        $caseClauses = collect($roleMap)
            ->map(fn (string $mappedRole, string $role) => "WHEN '{$role}' THEN '{$mappedRole}'")
            ->implode("\n                    ");

        $backupColumns = collect(DB::select("PRAGMA table_info('{$backupTable}')"))
            ->pluck('name')
            ->all();

        $select = static function (string $column, string $fallback) use ($backupColumns): string {
            return in_array($column, $backupColumns, true) ? $column : $fallback;
        };

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
                    {$caseClauses}
                    ELSE COALESCE(role, '{$legacyRole}')
                END,
                COALESCE({$select('money', '0')}, 0),
                {$select('remember_token', 'NULL')},
                {$select('created_at', 'NULL')},
                {$select('updated_at', 'NULL')},
                COALESCE({$select('status', "'active'")}, 'active'),
                {$select('phone', 'NULL')},
                {$select('documento', 'NULL')}
            FROM {$backupTable}
        SQL);

        Schema::drop($backupTable);
        DB::statement("DELETE FROM sqlite_sequence WHERE name = 'users'");
        DB::statement("INSERT INTO sqlite_sequence (name, seq) SELECT 'users', COALESCE(MAX(id), 0) FROM users");
        DB::statement('PRAGMA foreign_keys=ON');
    }
};
