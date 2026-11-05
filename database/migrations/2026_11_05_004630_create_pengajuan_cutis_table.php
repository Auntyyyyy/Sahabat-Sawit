<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();

            // contoh: "Cuti Tahunan", "Cuti Sakit", "Cuti Pribadi", "Cuti Lainnya"
            $table->string('jenis_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            // jumlah hari cuti yang dihitung (dihitung saat pengajuan disimpan)
            $table->unsignedSmallInteger('jumlah_hari');
            $table->text('keterangan')->nullable();

            // status: menunggu, disetujui, ditolak
            $table->string('status')->default('menunggu');
            // true kalau disetujui otomatis oleh aturan (cuti sakit atau cuti 1 hari), bukan oleh HR
            $table->boolean('otomatis')->default(false);

            // diisi saat HR memutuskan; kosong untuk persetujuan otomatis
            $table->foreignId('diputuskan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diputuskan_pada')->nullable();
            $table->text('alasan_tolak')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index(['karyawan_id', 'tanggal_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cutis');
    }
};