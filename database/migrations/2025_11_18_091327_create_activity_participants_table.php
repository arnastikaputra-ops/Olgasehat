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
        Schema::create('activity_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_peserta'); // Nama peserta yang mendaftar
            $table->string('bukti_pembayaran')->nullable(); // File bukti pembayaran jika event berbayar
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status verifikasi pembayaran
            $table->text('catatan')->nullable(); // Catatan dari admin atau peserta
            $table->timestamps();
            
            // Unique constraint: satu user hanya bisa mendaftar sekali per event
            $table->unique(['activity_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_participants');
    }
};
