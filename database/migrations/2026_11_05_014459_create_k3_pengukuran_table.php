<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Pengukuran Lingkungan kerja (kebisingan, pencahayaan, suhu, debu, gas, dan sebagainya).
     * Setiap baris adalah satu hasil pengukuran pada satu titik.
     * Dashboard K3 membaca jumlah baris dan jumlah titik yang berbeda.
     */
    public function up(): void
    {
        Schema::create('k3_pengukuran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');                                  // tanggal pengukuran
            $table->string('titik');                                  // titik / lokasi pengukuran
            $table->string('parameter', 50);                          // kebisingan, pencahayaan, suhu, dan seterusnya
            $table->decimal('nilai', 12, 3);                          // hasil ukur
            $table->string('satuan', 20);                             // dB, lux, °C, mg/m³, ppm, dan seterusnya
            $table->decimal('nilai_batas', 12, 3)->nullable();        // nilai ambang batas / baku mutu (opsional)
            $table->string('arah_batas', 10)->default('maksimum');    // maksimum = tidak boleh lebih, minimum = tidak boleh kurang
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index('tanggal');
            $table->index('titik');
            $table->index('parameter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_pengukuran');
    }
};