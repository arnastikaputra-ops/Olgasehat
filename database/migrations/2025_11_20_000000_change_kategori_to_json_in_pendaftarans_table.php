<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pendaftarans')) {
            return;
        }
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Convert existing string kategori to JSON array
            $venues = DB::table('pendaftarans')->whereNotNull('kategori')->get();
            foreach ($venues as $venue) {
                if ($venue->kategori && !empty($venue->kategori)) {
                    // Check if already JSON array
                    $decoded = json_decode($venue->kategori, true);
                    if (is_array($decoded)) {
                        // Already JSON array, just encode it back
                        $kategoriArray = json_encode($decoded);
                    } else {
                        // Still string, convert to JSON array
                        $kategoriArray = json_encode([$venue->kategori]);
                    }
                    DB::table('pendaftarans')
                        ->where('id', $venue->id)
                        ->update(['kategori' => $kategoriArray]);
                }
            }
        });

        // Change column type to JSON
        Schema::table('pendaftarans', function (Blueprint $table) {
            DB::statement('ALTER TABLE pendaftarans MODIFY kategori JSON NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('pendaftarans')) {
            return;
        }
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Convert JSON back to string (take first item)
            $venues = DB::table('pendaftarans')->whereNotNull('kategori')->get();
            foreach ($venues as $venue) {
                if ($venue->kategori) {
                    // Handle both string (already decoded) and JSON string
                    if (is_string($venue->kategori)) {
                        $kategoriArray = json_decode($venue->kategori, true);
                        if (is_array($kategoriArray) && !empty($kategoriArray)) {
                            $firstKategori = $kategoriArray[0];
                            DB::table('pendaftarans')
                                ->where('id', $venue->id)
                                ->update(['kategori' => $firstKategori]);
                        }
                    } elseif (is_array($venue->kategori) && !empty($venue->kategori)) {
                        // If already array, take first item
                        $firstKategori = $venue->kategori[0];
                        DB::table('pendaftarans')
                            ->where('id', $venue->id)
                            ->update(['kategori' => $firstKategori]);
                    }
                }
            }
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            DB::statement('ALTER TABLE pendaftarans MODIFY kategori VARCHAR(255) NULL');
        });
    }
};

