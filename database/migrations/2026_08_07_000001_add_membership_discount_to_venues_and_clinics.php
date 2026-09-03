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
            if (!Schema::hasColumn('pendaftarans', 'is_membership_discount')) {
                $table->boolean('is_membership_discount')->default(true)->after('komisi_nilai');
            }
            if (!Schema::hasColumn('pendaftarans', 'membership_discount_percent')) {
                $table->decimal('membership_discount_percent', 5, 2)->default(10.00)->after('is_membership_discount');
            }
        });

        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'is_membership_discount')) {
                $table->boolean('is_membership_discount')->default(true)->after('komisi_nilai');
            }
            if (!Schema::hasColumn('clinics', 'membership_discount_percent')) {
                $table->decimal('membership_discount_percent', 5, 2)->default(10.00)->after('is_membership_discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['is_membership_discount', 'membership_discount_percent']);
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['is_membership_discount', 'membership_discount_percent']);
        });
    }
};
