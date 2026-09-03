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
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'nama_bank')) {
                $table->string('nama_bank')->nullable()->after('membership_discount_percent');
            }
            if (!Schema::hasColumn('pendaftarans', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('nama_bank');
            }
            if (!Schema::hasColumn('pendaftarans', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening')->nullable()->after('nomor_rekening');
            }
        });

        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'nama_bank')) {
                $table->string('nama_bank')->nullable()->after('membership_discount_percent');
            }
            if (!Schema::hasColumn('clinics', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('nama_bank');
            }
            if (!Schema::hasColumn('clinics', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening')->nullable()->after('nomor_rekening');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'nomor_rekening', 'nama_pemilik_rekening']);
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'nomor_rekening', 'nama_pemilik_rekening']);
        });
    }
};
