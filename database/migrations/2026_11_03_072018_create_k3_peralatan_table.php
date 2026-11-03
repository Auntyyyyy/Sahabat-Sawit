<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Peralatan Keselamatan (APD, APAR, P3K) dalam satu tabel.
     *
     * Dashboard K3 membaca kolom: kategori, jumlah, lokasi, tanggal_kedaluwarsa.
     *  - APD  : satu baris = satu jenis APD, jumlah = stok
     *  - APAR : satu baris = satu tabung (jumlah selalu 1)
     *  - P3K  : satu baris = satu kotak/ruang P3K, jumlah = jumlah item standar di dalamnya
     */
    public function up(): void
    {
        Schema::create('k3_peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 10);                      // APD / APAR / P3K
            $table->string('nama');
            $table->string('kode', 50)->nullable();              // kode tabung APAR / kode kotak P3K
            $table->string('lokasi')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('jenis_media', 50)->nullable();       // khusus APAR: powder, CO2, busa, dan seterusnya
            $table->string('ukuran', 50)->nullable();            // contoh: 6 kg, atau ukuran APD
            $table->date('tanggal_isi_terakhir')->nullable();    // khusus APAR
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->string('kondisi', 20)->default('baik');      // baik / perlu_perbaikan / rusak
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index(['kategori', 'tanggal_kedaluwarsa']);
            $table->index('lokasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_peralatan');
    }
};