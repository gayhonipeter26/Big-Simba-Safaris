<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // game_drive, hostel, transfer, etc.
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('pricing_tier')->nullable(); // e.g., "Per Person", "Per Group"
            $table->decimal('starting_price', 12, 2)->default(0);
            $table->text('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
