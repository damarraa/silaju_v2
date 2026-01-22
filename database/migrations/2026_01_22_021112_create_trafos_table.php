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
        Schema::create('trafos', function (Blueprint $table) {
            $table->id();
            // Identitas & Listrik
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('id_gardu')->unique()->index();
            $table->string('sr')->nullable();
            $table->string('daya');
            $table->string('merk');

            // Lokasi
            $table->text('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Bukti Foto
            $table->string('evidence')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trafos');
    }
};
