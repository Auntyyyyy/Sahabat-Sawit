<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Safety Patrol: setiap baris adalah satu item hasil inspeksi.
     * Dashboard K3 membaca kolom hasil dan status_tindak untuk menghitung
     * kepatuhan patrol dan jumlah temuan yang belum selesai.
     */
    public function up(): void
    {
        Schema::create('k3_patrol', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('lokasi');
            $table->text('uraian');                                   // yang diperiksa / ditemukan
            $table->string('hasil', 10);                              // sesuai atau temuan
            $table->string('status_tindak', 10)->default('selesai');  // terbuka atau selesai
            $table->text('tindakan')->nullable();                     // tindakan perbaikan
            $table->string('foto')->nullable();                       // path foto di storage/app/public
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas pencatat
            $table->timestamps();

            $table->index(['hasil', 'status_tindak']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_patrol');
    }
};