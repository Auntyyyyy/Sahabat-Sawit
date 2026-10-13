<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daftar barang ATK + stok saat ini
        Schema::create('atk_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('kategori')->nullable();
            $table->string('satuan', 30)->default('pcs');
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Riwayat barang masuk (menambah stok)
        Schema::create('atk_stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_item_id')->constrained('atk_items')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->date('tanggal');
            $table->string('catatan')->nullable(); // mis. nama toko / nomor nota
            $table->timestamps();
        });

        // Permintaan ATK dari karyawan
        Schema::create('atk_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->nullOnDelete();
            $table->string('pemohon_nama');          // salinan, supaya riwayat tetap terbaca
            $table->string('pemohon_divisi')->nullable();
            $table->date('tanggal');
            $table->string('status')->default('Diajukan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Isi permintaan (satu permintaan bisa banyak barang)
        Schema::create('atk_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_request_id')->constrained('atk_requests')->cascadeOnDelete();
            $table->foreignId('atk_item_id')->constrained('atk_items')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atk_request_items');
        Schema::dropIfExists('atk_requests');
        Schema::dropIfExists('atk_stock_ins');
        Schema::dropIfExists('atk_items');
    }
};