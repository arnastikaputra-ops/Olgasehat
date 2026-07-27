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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('reviews', 'tipe_target')) {
                $table->string('tipe_target', 50)->default('platform')->after('user_id')->comment('platform, venue, klinik, komunitas, event');
            }
            if (!Schema::hasColumn('reviews', 'target_id')) {
                $table->unsignedBigInteger('target_id')->nullable()->after('tipe_target');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id', 'tipe_target', 'target_id']);
            }
        });
    }
};
