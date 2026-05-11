<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('karts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kart_type_id')->constrained('kart_types')->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('status', 20)->default('Available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karts');
    }
};
