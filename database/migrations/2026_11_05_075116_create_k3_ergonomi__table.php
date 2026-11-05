<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Ergonomi: setiap baris adalah satu penilaian risiko ergonomi untuk satu pekerjaan.
     * Sengaja TIDAK menyimpan nama karyawan, hanya pekerjaan, bagian, dan lokasi.
     * Dashboard K3 membaca tingkat_risiko dan status_tindak untuk penilaian berisiko tinggi
     * yang belum ditindaklanjuti.
     */
    public function up(): void
    {
        Schema::create('k3_ergonomi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');                                  // tanggal penilaian
            $table->string('pekerjaan');                              // pekerjaan atau aktivitas yang dinilai
            $table->string('bagian')->nullable();                     // bagian / unit kerja
            $table->string('lokasi')->nullable();
            $table->string('metode', 20);                             // REBA / RULA / OWAS / Lainnya
            $table->unsignedSmallInteger('skor')->nullable();         // skor akhir menurut metode
            $table->string('tingkat_risiko', 15);                     // rendah / sedang / tinggi / sangat_tinggi
            $table->text('rekomendasi')->nullable();                  // rekomendasi perbaikan
            $table->string('pic')->nullable();                        // penanggung jawab tindak lanjut
            $table->date('target_tanggal')->nullable();               // target selesai tindak lanjut
            $table->string('status_tindak', 10)->default('terbuka');  // terbuka / selesai
            $table->date('tanggal_selesai')->nullable();              // realisasi selesai
            $table->text('keterangan')->nullable();                   // catatan penilai
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index('tanggal');
            $table->index(['tingkat_risiko', 'status_tindak']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_ergonomi');
    }
};