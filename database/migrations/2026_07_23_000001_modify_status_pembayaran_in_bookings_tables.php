<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE venue_bookings MODIFY COLUMN status_pembayaran VARCHAR(50) NOT NULL DEFAULT 'pending_acc'");
        } catch (\Throwable $e) {
            // Fallback for sqlite or other DBs if needed
            Schema::table('venue_bookings', function (Blueprint $table) {
                $table->string('status_pembayaran', 50)->default('pending_acc')->change();
            });
        }

        try {
            DB::statement("ALTER TABLE health_bookings MODIFY COLUMN status_pembayaran VARCHAR(50) NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            Schema::table('health_bookings', function (Blueprint $table) {
                $table->string('status_pembayaran', 50)->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert to strict enum
    }
};
