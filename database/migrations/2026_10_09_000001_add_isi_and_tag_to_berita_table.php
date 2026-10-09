<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            // Isi lengkap berita (HTML dari editor teks). Nullable supaya berita lama tetap aman.
            if (! Schema::hasColumn('berita', 'isi')) {
                $table->longText('isi')->nullable()->after('deskripsi_singkat');
            }

            // Label kecil di bawah ringkasan, contoh: "MoU". Boleh kosong.
            if (! Schema::hasColumn('berita', 'tag')) {
                $table->string('tag', 50)->nullable()->after('isi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'tag')) {
                $table->dropColumn('tag');
            }
            if (Schema::hasColumn('berita', 'isi')) {
                $table->dropColumn('isi');
            }
        });
    }
};