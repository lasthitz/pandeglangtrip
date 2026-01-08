<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('tickets', 'price')) {
                $table->integer('price')->default(0);
            }
            if (!Schema::hasColumn('tickets', 'description')) {
                $table->text('description');
            }
            if (!Schema::hasColumn('tickets', 'visit_date')) {
                $table->date('visit_date')->nullable();
            }
            if (!Schema::hasColumn('tickets', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('tickets', 'approval_status')) {
                $table->string('approval_status')->default('PENDING');
            }
            if (!Schema::hasColumn('tickets', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }
            if (!Schema::hasColumn('tickets', 'created_at') && !Schema::hasColumn('tickets', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        // aman: tidak men-drop kolom existing project (biar gak ngerusak T5/T6/T7)
    }
};
