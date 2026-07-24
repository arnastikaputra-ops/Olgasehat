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
        Schema::create('lapangan_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangans')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('harga')->default(0);
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available');
            $table->boolean('is_promo')->default(false);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan_slots');
    }
};

