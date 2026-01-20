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
        Schema::table('users', function (Blueprint $table) {
            $table->string('identity_number', 20)->after('id')->unique();
            $table->foreignId('rayon_id')->nullable()->after('identity_number')->constrained();
            $table->string('username')->after('email_verified_at')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rayon_id');
            $table->dropColumn('identity_number');
        });
    }
};
