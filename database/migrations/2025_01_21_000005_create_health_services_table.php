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
        Schema::create('health_services', function (Blueprint $table) {
            $table->id();
            
            $table->string('nama'); // "Konsultasi Dokter Umum"
            $table->text('deskripsi')->nullable();
            $table->string('kategori'); // "Konsultasi", "Medical Check-Up", "Fisioterapi"
            
            // Harga
            $table->enum('tipe_harga', ['gratis', 'berbayar'])->default('berbayar');
            $table->decimal('harga', 15, 2)->nullable();
            $table->integer('durasi')->default(30); // Menit
            
            // Relasi
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
            // Bisa layanan umum tanpa dokter spesifik
            
            // Status
            $table->boolean('aktif')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_services');
    }
};

