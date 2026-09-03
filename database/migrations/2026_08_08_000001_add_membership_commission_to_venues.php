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
            if (!Schema::hasColumn('pendaftarans', 'membership_komisi_tipe')) {
                $table->enum('membership_komisi_tipe', ['none', 'percentage', 'fixed'])->default('percentage')->after('komisi_nilai');
            }
            if (!Schema::hasColumn('pendaftarans', 'membership_komisi_nilai')) {
                $table->decimal('membership_komisi_nilai', 15, 2)->default(0)->after('membership_komisi_tipe');
            }
        });

        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'membership_komisi_tipe')) {
                $table->enum('membership_komisi_tipe', ['none', 'percentage', 'fixed'])->default('percentage')->after('komisi_nilai');
            }
            if (!Schema::hasColumn('clinics', 'membership_komisi_nilai')) {
                $table->decimal('membership_komisi_nilai', 15, 2)->default(0)->after('membership_komisi_tipe');
            }
        });

        Schema::table('mitras', function (Blueprint $table) {
            if (!Schema::hasColumn('mitras', 'membership_komisi_tipe')) {
                $table->enum('membership_komisi_tipe', ['none', 'percentage', 'fixed'])->default('percentage')->after('komisi_nilai');
            }
            if (!Schema::hasColumn('mitras', 'membership_komisi_nilai')) {
                $table->decimal('membership_komisi_nilai', 15, 2)->default(0)->after('membership_komisi_tipe');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['membership_komisi_tipe', 'membership_komisi_nilai']);
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['membership_komisi_tipe', 'membership_komisi_nilai']);
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn(['membership_komisi_tipe', 'membership_komisi_nilai']);
        });
    }
};
