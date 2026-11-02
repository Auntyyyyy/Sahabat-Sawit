<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Monitoring Kesehatan: setiap baris adalah satu kali pemeriksaan kesehatan
     * (MCU) seorang karyawan. Sengaja HANYA menyimpan status kelayakan dan catatan umum,
     * tidak menyimpan diagnosis atau hasil laboratorium.
     */
    public function up(): void
    {
        Schema::create('k3_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                                 // nama karyawan
            $table->string('jabatan')->nullable();                  // jabatan / bagian
            $table->date('tanggal');                                // tanggal pemeriksaan
            $table->string('jenis', 20);                            // awal / berkala / khusus
            $table->string('hasil', 30);                            // fit / fit_catatan / tidak_fit_sementara / tidak_fit
            $table->text('catatan')->nullable();                    // catatan umum, tanpa diagnosis
            $table->date('tanggal_berikutnya')->nullable();         // jadwal pemeriksaan berikutnya
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index('nama');
            $table->index('tanggal');
            $table->index('tanggal_berikutnya');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_kesehatan');
    }
};