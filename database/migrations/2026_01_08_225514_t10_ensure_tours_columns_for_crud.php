<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Catatan: project lu sudah punya semua kolom ini.
            // Migration ini dibuat "aman" (no-op) kalau kolom sudah ada.

            if (!Schema::hasColumn('tours', 'name')) {
                $table->string('name');
            }

            if (!Schema::hasColumn('tours', 'price_per_person')) {
                $table->unsignedBigInteger('price_per_person');
            }

            if (!Schema::hasColumn('tours', 'description')) {
                $table->text('description');
            }

            if (!Schema::hasColumn('tours', 'start_date')) {
                $table->date('start_date')->nullable();
            }

            if (!Schema::hasColumn('tours', 'end_date')) {
                $table->date('end_date')->nullable();
            }

            if (!Schema::hasColumn('tours', 'guide_name')) {
                $table->string('guide_name')->nullable();
            }

            if (!Schema::hasColumn('tours', 'itinerary')) {
                $table->text('itinerary')->nullable();
            }

            if (!Schema::hasColumn('tours', 'image_path')) {
                $table->string('image_path')->nullable();
            }

            if (!Schema::hasColumn('tours', 'approval_status')) {
                $table->string('approval_status')->default('PENDING')->index();
            }

            if (!Schema::hasColumn('tours', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }

            if (!Schema::hasColumn('tours', 'created_at') && !Schema::hasColumn('tours', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        // Sesuai gaya aman: tidak drop apa pun untuk menghindari ngerusak tabel existing dari T5.
        // Blueprint tidak mewajibkan rollback bersih untuk tab ini.
    }
};
