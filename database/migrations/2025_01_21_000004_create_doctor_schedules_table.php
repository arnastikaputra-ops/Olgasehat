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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            
            // Jadwal
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_mulai'); // "08:00"
            $table->time('jam_selesai'); // "14:00"
            
            // Kuota & Durasi
            $table->integer('durasi_konsultasi')->default(30); // Menit
            $table->integer('kuota_per_hari')->default(20); // Maksimal pasien per hari
            
            $table->boolean('aktif')->default(true);
            
            $table->timestamps();
            
            // Unique: satu dokter tidak bisa double jadwal di hari & waktu yang sama
            $table->unique(['doctor_id', 'clinic_id', 'hari', 'jam_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};

