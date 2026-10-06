<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset')->unique();
            $table->string('nama');
            $table->string('kategori'); // Elektronik, Kendaraan, Furnitur, Peralatan Kantor, Peralatan Operasional, Lainnya
            $table->string('lokasi')->nullable(); // mis. Kantor Medan, Site Rokan Hilir
            $table->string('penanggung_jawab')->nullable(); // nama/divisi pemegang aset
            $table->string('kondisi')->default('Baik'); // Baik, Rusak Ringan, Rusak Berat, Dalam Perbaikan
            $table->string('status')->default('Digunakan'); // Digunakan, Disimpan, Dipinjamkan, Dihapuskan
            $table->date('tanggal_perolehan')->nullable();
            $table->decimal('nilai_perolehan', 15, 2)->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};