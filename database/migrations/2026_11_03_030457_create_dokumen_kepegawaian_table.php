<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_kepegawaian', function (Blueprint $table) {
            $table->id();

            // Kosong = dokumen perusahaan (SOP, kebijakan, formulir).
            // Terisi = dokumen milik satu karyawan (KTP, ijazah, dan sebagainya).
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->cascadeOnDelete();

            $table->string('judul');
            $table->string('kategori');
            $table->text('keterangan')->nullable();

            // File disimpan di penyimpanan privat (storage/app/dokumen), bukan folder publik
            $table->string('berkas_path');
            $table->string('nama_asli');
            $table->unsignedBigInteger('ukuran')->default(0);   // dalam byte
            $table->string('tipe_mime')->nullable();

            // Opsional: untuk dokumen yang punya masa berlaku (sertifikat, SIM, dan sebagainya)
            $table->date('tanggal_kedaluwarsa')->nullable();

            $table->foreignId('diunggah_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['karyawan_id', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_kepegawaian');
    }
};