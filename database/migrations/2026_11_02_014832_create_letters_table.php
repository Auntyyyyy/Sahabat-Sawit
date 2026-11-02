<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->index();
            $table->string('arah'); // Masuk, Keluar
            $table->string('jenis'); // Surat Permohonan, Surat Undangan, dst.
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima')->nullable(); // hanya untuk surat masuk
            $table->string('pengirim_tujuan');
            $table->string('perihal');
            $table->string('sifat')->default('Biasa'); // Biasa, Penting, Segera, Rahasia
            $table->string('status')->default('Baru'); // Baru, Diproses, Selesai, Diarsipkan
            $table->string('lampiran')->nullable();
            $table->string('lampiran_nama')->nullable(); // nama asli file saat diunggah
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};