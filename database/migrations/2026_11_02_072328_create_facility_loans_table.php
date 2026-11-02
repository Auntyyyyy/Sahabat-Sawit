<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_loans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->foreignId('facility_id')->constrained('facilities')->restrictOnDelete();
            $table->string('peminjam_nama');
            $table->string('peminjam_divisi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedInteger('jumlah_orang')->nullable();
            $table->string('keperluan');
            $table->string('status')->default('Diajukan');
            $table->text('catatan')->nullable();

            // Diisi saat fasilitas dikembalikan
            $table->date('tanggal_kembali')->nullable();
            $table->string('kondisi_kembali')->nullable();
            $table->text('catatan_kembali')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_loans');
    }
};