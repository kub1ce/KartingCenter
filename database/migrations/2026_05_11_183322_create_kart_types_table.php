<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kart_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_age');
            $table->integer('max_age')->nullable();
            $table->integer('min_height');
            $table->integer('seats');
            $table->decimal('price_modifier', 4, 2)->default(1.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kart_types');
    }
};
