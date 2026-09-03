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
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'pendaftaran_id')) {
                $table->foreignId('pendaftaran_id')->nullable()->after('pemilik_id')->constrained('pendaftarans')->onDelete('set null');
            }
            if (!Schema::hasColumn('activities', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->after('pendaftaran_id')->constrained('clinics')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'pendaftaran_id')) {
                $table->dropForeign(['pendaftaran_id']);
                $table->dropColumn('pendaftaran_id');
            }
            if (Schema::hasColumn('activities', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            }
        });
    }
};
