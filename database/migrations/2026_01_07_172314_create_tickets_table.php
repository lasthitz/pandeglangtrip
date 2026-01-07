<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('price');
            $table->text('description');
            $table->date('visit_date')->nullable();
            $table->string('image_path')->nullable(); // hanya simpan path
            $table->boolean('is_active')->default(false);
            $table->string('approval_status')->default('PENDING'); // PENDING/APPROVED/REJECTED
            $table->timestamps();

            $table->index(['approval_status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
