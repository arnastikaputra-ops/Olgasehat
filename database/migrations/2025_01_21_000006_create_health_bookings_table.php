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
        Schema::create('health_bookings', function (Blueprint $table) {
            $table->id();
            
            // Kode Booking
            $table->string('kode_booking')->unique(); // "BK-20250118-001"
            
            // Relasi
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('health_services')->onDelete('set null');
            
            // Jadwal
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('durasi')->default(30); // Menit
            
            // Informasi Pasien
            $table->string('nama_pasien');
            $table->string('nomor_telepon');
            $table->string('email')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('keluhan')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('alergi')->nullable();
            
            // Status
            $table->enum('status', [
                'pending',      // Menunggu konfirmasi
                'confirmed',    // Dikonfirmasi
                'completed',    // Selesai
                'cancelled',    // Dibatalkan
                'no_show'       // Tidak datang
            ])->default('pending');
            
            // Pembayaran
            $table->enum('metode_pembayaran', ['cash', 'bpjs', 'asuransi', 'transfer'])->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->enum('status_pembayaran', ['pending', 'paid', 'refunded'])->default('pending');
            
            // Catatan
            $table->text('catatan')->nullable();
            $table->text('catatan_dokter')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['clinic_id', 'tanggal', 'status']);
            $table->index(['doctor_id', 'tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_bookings');
    }
};

