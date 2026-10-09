<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            // Jumlah berapa kali berita dibuka. Berita yang sudah ada mulai dari 0.
            if (! Schema::hasColumn('berita', 'views')) {
                $table->unsignedBigInteger('views')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
};