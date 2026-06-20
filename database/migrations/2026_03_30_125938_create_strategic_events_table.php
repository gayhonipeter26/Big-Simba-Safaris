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
        Schema::create('strategic_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('occasion')->nullable();
            $table->tinyInteger('month'); // 0-11
            $table->json('days'); // array of days
            $table->string('color')->default('bg-white');
            $table->string('label')->default('Event'); // Peak, Event, Optimal
            $table->string('location');
            $table->string('region')->nullable();
            $table->string('time')->nullable();
            $table->text('description')->nullable();
            $table->string('asset_class')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('published'); // draft, published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategic_events');
    }
};
