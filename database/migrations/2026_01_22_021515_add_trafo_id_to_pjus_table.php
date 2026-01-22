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
            $table->foreignId('trafo_id')
                ->nullable()
                ->after('user_id')
                ->constrained('trafos')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjus', function (Blueprint $table) {
            $table->dropForeign(['trafo_id']);
            $table->dropColumn('trafo_id');
        });
    }
};
