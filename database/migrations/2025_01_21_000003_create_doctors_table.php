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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            
            // Informasi Dokter
            $table->string('nama'); // "dr. Kukuh Prasetyo"
            $table->string('gelar')->nullable(); // "dr.", "drg.", "Sp.PD"
            $table->string('spesialisasi'); // "Dokter Umum", "Dokter Gigi"
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            
            // Kualifikasi
            $table->string('nomor_str')->nullable(); // Surat Tanda Registrasi
            $table->string('pendidikan')->nullable();
            $table->text('pengalaman')->nullable();
            
            // Relasi
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            // Satu dokter bisa bekerja di beberapa klinik (many-to-many)
            // Tapi untuk simpel, kita pakai one-to-many dulu
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('aktif')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

