<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fasilitas')->unique();
            $table->string('nama');
            $table->string('kategori'); // Ruangan/Gedung, Kendaraan Dinas, Peralatan Kantor, Mess/Perumahan, Sarana Umum, Lainnya
            $table->string('lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable(); // nama/divisi penanggung jawab atau pengguna
            $table->string('kondisi')->default('Baik'); // Baik, Rusak Ringan, Rusak Berat, Dalam Perbaikan
            $table->unsignedInteger('kapasitas')->nullable();
            $table->date('tanggal_perawatan_terakhir')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};