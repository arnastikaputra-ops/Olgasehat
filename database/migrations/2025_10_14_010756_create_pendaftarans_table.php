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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();

            // Step 1
            $table->string('namavenue');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kategori');
            $table->string('logo'); 

            // Step 2
            $table->string('video_review')->nullable(); // link YouTube
            $table->longText('detail')->nullable();
            $table->longText('aturan')->nullable();
            $table->string('lokasi')->nullable(); // link Google Maps
            $table->json('fasilitas')->nullable(); // checkbox multiple value

            // Step 3
            $table->boolean('syarat_disetujui')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
