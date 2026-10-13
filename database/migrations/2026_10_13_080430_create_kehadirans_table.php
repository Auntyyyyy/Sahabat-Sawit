<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            // status: hadir, izin, cuti, alpha
            $table->string('status')->default('hadir');
            $table->unsignedInteger('menit_terlambat')->default(0);
            // dipakai hanya kalau status = izin, contoh: "Sakit", "Pribadi", "Kepentingan"
            $table->string('jenis_izin')->nullable();
            // dipakai hanya kalau status = cuti, contoh: "Cuti Tahunan", "Cuti Sakit", "Cuti Pribadi", "Cuti Lainnya"
            $table->string('jenis_cuti')->nullable();
            $table->decimal('jam_lembur', 5, 2)->default(0);
            $table->timestamps();

            // satu karyawan cuma boleh punya satu catatan per tanggal
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};