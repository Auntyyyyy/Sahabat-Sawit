<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Program K3L (Keselamatan, Kesehatan Kerja, dan Lingkungan).
     * Setiap baris adalah satu program kerja dalam satu tahun.
     * Dashboard K3 membaca kolom tahun dan status untuk menghitung progres program.
     */
    public function up(): void
    {
        Schema::create('k3_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun');                // tahun program kerja
            $table->string('nama');                               // nama program
            $table->string('kategori', 20);                       // keselamatan / kesehatan / lingkungan / lainnya
            $table->string('pic')->nullable();                    // penanggung jawab
            $table->date('target_tanggal')->nullable();           // target selesai
            $table->string('status', 15)->default('rencana');     // rencana / berjalan / selesai
            $table->date('tanggal_selesai')->nullable();          // realisasi selesai
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index(['tahun', 'status']);
            $table->index('target_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_program');
    }
};