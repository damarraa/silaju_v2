<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pjus', function (Blueprint $table) {
            $table->foreignId('rayon_id')->nullable()->after('user_id')->constrained('rayons')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->after('user_id')->constrained('areas')->nullOnDelete();
        });

        Schema::table('trafos', function (Blueprint $table) {
            $table->foreignId('rayon_id')->nullable()->after('user_id')->constrained('rayons')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->after('user_id')->constrained('areas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjus', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['rayon_id']);
            $table->dropColumn(['area_id', 'rayon_id']);
        });

        Schema::table('trafos', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['rayon_id']);
            $table->dropColumn(['area_id', 'rayon_id']);
        });
    }
};
