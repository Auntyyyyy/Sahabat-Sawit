<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            // Selalu diisi tanggal 1 di bulan yang dinilai, contoh: 2026-10-01
            $table->date('periode');
            // Target yang ditetapkan & capaian aktual (satuan bebas: unit, rupiah, poin KPI, dst)
            $table->decimal('target', 12, 2)->default(0);
            $table->decimal('realisasi', 12, 2)->default(0);
            // Skor penilaian kinerja skala 1.0 - 5.0
            $table->decimal('skor', 2, 1)->default(0);
            $table->timestamps();

            // satu karyawan hanya punya satu penilaian per bulan
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerjas');
    }
};