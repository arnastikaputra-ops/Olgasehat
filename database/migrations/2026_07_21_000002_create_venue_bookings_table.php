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
        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->json('slot_ids'); // Array of LapanganSlot IDs
            $table->string('nama_pemesan');
            $table->string('nomor_telepon');
            $table->string('email')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->enum('komisi_tipe', ['none', 'percentage', 'fixed'])->default('none');
            $table->decimal('komisi_nilai', 15, 2)->default(0);
            $table->decimal('komisi_platform', 15, 2)->default(0);
            $table->decimal('pendapatan_mitra', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('metode_pembayaran')->default('virtualAccount');
            $table->string('bank_code')->nullable(); // e.g. BCA, BNI, MANDIRI, BRI, PERMATA, DANA, QRIS
            $table->string('virtual_account')->nullable();
            $table->enum('status_pembayaran', ['pending', 'paid', 'cancelled'])->default('paid');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Extend health_bookings with commission & payment fields if needed
        Schema::table('health_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('health_bookings', 'komisi_tipe')) {
                $table->enum('komisi_tipe', ['none', 'percentage', 'fixed'])->default('none')->after('total_harga');
            }
            if (!Schema::hasColumn('health_bookings', 'komisi_nilai')) {
                $table->decimal('komisi_nilai', 15, 2)->default(0)->after('komisi_tipe');
            }
            if (!Schema::hasColumn('health_bookings', 'komisi_platform')) {
                $table->decimal('komisi_platform', 15, 2)->default(0)->after('komisi_nilai');
            }
            if (!Schema::hasColumn('health_bookings', 'pendapatan_mitra')) {
                $table->decimal('pendapatan_mitra', 15, 2)->default(0)->after('komisi_platform');
            }
            if (!Schema::hasColumn('health_bookings', 'virtual_account')) {
                $table->string('virtual_account')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('health_bookings', 'bank_code')) {
                $table->string('bank_code')->nullable()->after('virtual_account');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_bookings');

        Schema::table('health_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'komisi_tipe',
                'komisi_nilai',
                'komisi_platform',
                'pendapatan_mitra',
                'virtual_account',
                'bank_code',
            ]);
        });
    }
};
