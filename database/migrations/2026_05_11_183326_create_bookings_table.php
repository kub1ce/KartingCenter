<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained()->cascadeOnDelete();
            $table->integer('participants_count');
            $table->string('status', 20)->default('Pending');
            $table->decimal('total_price', 10, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('time_slot_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
