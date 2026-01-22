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
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])
                ->default('pending')
                ->after('evidence');

            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjus', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verification_status', 'verified_at', 'verified_by']);
        });
    }
};
