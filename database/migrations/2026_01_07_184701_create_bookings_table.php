<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_code')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Ticket or Tour (polymorphic)
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->index(['bookable_type', 'bookable_id']);

            $table->unsignedInteger('qty');
            $table->unsignedInteger('total_price');

            $table->string('payment_method'); // QRIS, DANA, OVO, GOPAY, TRANSFER_BANK
            $table->string('status')->default('PAID'); // dummy always PAID

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
