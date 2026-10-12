<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();      // disimpan sebagai salinan, supaya riwayat tetap terbaca walau user dihapus
            $table->string('action');                     // created | updated | deleted
            $table->string('subject_label');              // mis. Produk, Berita, Kegiatan CSR
            $table->string('subject_title');              // mis. Crude Palm Oil (CPO)
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};