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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama klinik
            $table->enum('tipe', ['klinik', 'layanan'])->default('klinik');
            // tipe: 'klinik' = klinik fisik, 'layanan' = layanan kesehatan umum
            
            // Informasi Dasar
            $table->string('slug')->unique(); // URL friendly
            $table->text('deskripsi')->nullable();
            $table->string('motto')->nullable(); // "MELAYANI DENGAN SENANG HATI"
            
            // Kontak & Lokasi
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable(); // "Kota Jakarta Timur"
            $table->string('provinsi')->nullable(); // "DKI Jakarta"
            $table->string('kode_pos')->nullable();
            $table->string('nomor_telepon')->nullable(); // "087877254639"
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Operasional
            $table->json('hari_operasional')->nullable(); // ["senin", "selasa", ...]
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            
            // Media
            $table->string('logo')->nullable(); // Logo klinik
            $table->string('foto_utama')->nullable(); // Foto utama klinik
            $table->json('galeri')->nullable(); // Array foto galeri
            
            // Kategori
            $table->foreignId('clinic_category_id')->nullable()->constrained('clinic_categories')->onDelete('set null');
            $table->json('layanan_tersedia')->nullable(); // ["Konsultasi", "Medical Check-Up", ...]
            
            // Status & Verifikasi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_reject')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Relasi
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // user_id = pemilik/admin klinik
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};

