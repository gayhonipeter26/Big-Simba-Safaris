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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_recon_at')->nullable();
            $table->string('last_recon_ip', 45)->nullable();
            $table->string('last_recon_country')->nullable();
            $table->string('last_recon_city')->nullable();
            $table->string('last_recon_browser')->nullable();
            $table->string('last_recon_os')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_recon_at', 'last_recon_ip', 'last_recon_country', 'last_recon_city', 'last_recon_browser', 'last_recon_os']);
        });
    }
};
