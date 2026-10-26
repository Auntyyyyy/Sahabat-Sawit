<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel dokumen keselamatan (SOP, kebijakan, laporan, dan sebagainya) untuk dashboard K3.
     */
    public function up(): void
    {
        Schema::create('k3_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 50);
            $table->date('tanggal');
            $table->string('file');                         // path file PDF di storage/app/public
            $table->string('status', 10)->default('aktif'); // aktif atau arsip
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // pengunggah
            $table->timestamps();

            $table->index(['status', 'kategori']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_dokumen');
    }
};