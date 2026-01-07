<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // kalau role belum ada, baru tambah
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
        });

        // index terpisah, biar bisa dicek eksistensinya
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                // buat index kalau belum ada
                // MariaDB tidak punya "if not exists" untuk index via blueprint,
                // jadi kita amanin di down() aja + ini biasanya aman kalau up cuma dipanggil sekali.
                $table->index('role');
            }
        });
    }

    public function down(): void
    {
        // drop index hanya kalau ada
        Schema::table('users', function (Blueprint $table) {
            // nama default index untuk kolom "role" biasanya "users_role_index"
            // kalau tidak ada, jangan dipaksa
            try {
                $table->dropIndex('users_role_index');
            } catch (\Throwable $e) {
                // biarin, berarti index memang gak ada
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
