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
        // Add to pendaftarans (venues)
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'komisi_tipe')) {
                $table->enum('komisi_tipe', ['none', 'percentage', 'fixed'])->default('none')->after('syarat_disetujui');
            }
            if (!Schema::hasColumn('pendaftarans', 'komisi_nilai')) {
                $table->decimal('komisi_nilai', 15, 2)->default(0)->after('komisi_tipe');
            }
        });

        // Add to clinics
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'komisi_tipe')) {
                $table->enum('komisi_tipe', ['none', 'percentage', 'fixed'])->default('none')->after('status');
            }
            if (!Schema::hasColumn('clinics', 'komisi_nilai')) {
                $table->decimal('komisi_nilai', 15, 2)->default(0)->after('komisi_tipe');
            }
        });

        // Add to mitras
        Schema::table('mitras', function (Blueprint $table) {
            if (!Schema::hasColumn('mitras', 'komisi_tipe')) {
                $table->enum('komisi_tipe', ['none', 'percentage', 'fixed'])->default('none')->after('status');
            }
            if (!Schema::hasColumn('mitras', 'komisi_nilai')) {
                $table->decimal('komisi_nilai', 15, 2)->default(0)->after('komisi_tipe');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['komisi_tipe', 'komisi_nilai']);
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['komisi_tipe', 'komisi_nilai']);
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn(['komisi_tipe', 'komisi_nilai']);
        });
    }
};
