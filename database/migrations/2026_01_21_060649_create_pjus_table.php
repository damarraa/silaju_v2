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
        Schema::create('pjus', function (Blueprint $table) {
            $table->id();
            // Informasi Identitas & Listrik
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('id_pelanggan')->nullable()->index();
            $table->string('daya')->nullable();
            $table->enum('status', ['meterisasi', 'non_meterisasi', 'ilegal']);

            // Informasi Lokasi
            $table->text('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Informasi Teknis Lampu
            $table->string('jenis_lampu');
            $table->string('merk_lampu');
            $table->integer('jumlah_lampu');
            $table->integer('watt');

            // Status & Operasional
            $table->enum('kondisi_lampu', ['baik', 'rusak']);
            $table->enum('tindak_lanjut', ['bongkar', 'putus', 'dibiarkan']);
            $table->enum('sistem_operasi', ['manual', 'photo_cell', 'timer']);
            $table->enum('installasi', ['kabel_tanah', 'kabel_udara']);
            $table->enum('kepemilikan', ['pemda', 'swadaya'])->default('pemda');
            $table->enum('peruntukan', ['jalan', 'taman', 'fasilitas_umum']);
            $table->boolean('nyala_siang')->default(0);

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
        Schema::dropIfExists('pjus');
    }
};
