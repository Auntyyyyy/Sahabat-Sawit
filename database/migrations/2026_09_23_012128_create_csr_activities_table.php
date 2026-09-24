<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('csr_category_id')->constrained()->cascadeOnDelete();
            $table->string('image')->nullable();
            // Dibiarkan string (bukan tipe date) karena datanya kadang berupa rentang,
            // contoh: "Januari - April 2026", bukan cuma satu tanggal.
            $table->string('date');
            $table->string('location');
            $table->string('title');
            $table->text('story');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_activities');
    }
};