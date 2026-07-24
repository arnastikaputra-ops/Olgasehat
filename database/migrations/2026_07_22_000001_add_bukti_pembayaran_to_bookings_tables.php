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
        if (Schema::hasTable('venue_bookings')) {
            Schema::table('venue_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('venue_bookings', 'bukti_pembayaran')) {
                    $table->string('bukti_pembayaran')->nullable()->after('catatan');
                }
            });
        }

        if (Schema::hasTable('health_bookings')) {
            Schema::table('health_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('health_bookings', 'bukti_pembayaran')) {
                    $table->string('bukti_pembayaran')->nullable()->after('catatan_dokter');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('venue_bookings')) {
            Schema::table('venue_bookings', function (Blueprint $table) {
                if (Schema::hasColumn('venue_bookings', 'bukti_pembayaran')) {
                    $table->dropColumn('bukti_pembayaran');
                }
            });
        }

        if (Schema::hasTable('health_bookings')) {
            Schema::table('health_bookings', function (Blueprint $table) {
                if (Schema::hasColumn('health_bookings', 'bukti_pembayaran')) {
                    $table->dropColumn('bukti_pembayaran');
                }
            });
        }
    }
};
