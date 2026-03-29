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
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('message'); // mpesa, visa
            $table->string('payment_status')->default('pending')->after('payment_method'); // pending, paid, failed
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->string('payment_id')->nullable()->after('payment_reference');
            $table->decimal('paid_amount', 10, 2)->nullable()->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_reference', 'payment_id', 'paid_amount']);
        });
    }
};
