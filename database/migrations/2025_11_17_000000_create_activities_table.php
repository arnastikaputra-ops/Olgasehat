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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama komunitas/aktivitas
            $table->string('kategori'); // Kategori olahraga
            $table->string('lokasi')->nullable(); // Lokasi kegiatan
            $table->enum('biaya_bergabung', ['gratis', 'berbayar'])->default('gratis'); // Biaya bergabung
            $table->unsignedBigInteger('harga')->nullable(); // Harga untuk aktivitas berbayar
            $table->text('deskripsi'); // Deskripsi lengkap
            $table->string('link_kontak')->nullable(); // Link grup/kontak (WhatsApp/IG)
            $table->string('banner')->nullable(); // Banner komunitas
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status verifikasi
            $table->string('jenis')->default('komunitas'); // Jenis: komunitas, membership, event
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // User yang membuat (bisa user biasa)
            $table->foreignId('pemilik_id')->nullable()->constrained('users')->onDelete('cascade'); // Pemilik lapangan yang membuat
            $table->foreignId('activity_type_id')->nullable()->constrained('activity_types')->onDelete('set null'); // Tipe aktivitas
            $table->text('alasan_reject')->nullable(); // Alasan jika ditolak
            $table->timestamp('verified_at')->nullable(); // Waktu verifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

