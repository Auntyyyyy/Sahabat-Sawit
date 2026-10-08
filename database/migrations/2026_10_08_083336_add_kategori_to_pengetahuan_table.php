<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel kategori
        Schema::create('pengetahuan_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // 2. Kolom kategori di tabel pengetahuan
        //    (nullable + nullOnDelete: kalau kategori dihapus, artikel TIDAK ikut terhapus)
        Schema::table('pengetahuan', function (Blueprint $table) {
            $table->foreignId('kategori_id')
                ->nullable()
                ->after('slug')
                ->constrained('pengetahuan_kategori')
                ->nullOnDelete();
        });

        // 3. Dua kategori awal
        $now = now();

        $tahukahId = DB::table('pengetahuan_kategori')->insertGetId([
            'nama' => 'Tahukah Anda?',
            'slug' => 'tahukah-anda',
            'order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('pengetahuan_kategori')->insert([
            'nama' => 'Fakta & Mitos',
            'slug' => 'fakta-mitos',
            'order' => 2,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Artikel yang sudah ada dimasukkan ke "Tahukah Anda?"
        DB::table('pengetahuan')->update(['kategori_id' => $tahukahId]);
    }

    public function down(): void
    {
        Schema::table('pengetahuan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });

        Schema::dropIfExists('pengetahuan_kategori');
    }
};