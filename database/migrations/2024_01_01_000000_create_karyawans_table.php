<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->date('birth_date')->nullable();
            $table->string('division');
            $table->string('position');
            $table->date('join_date');
            $table->enum('status', ['aktif', 'nonaktif', 'cuti'])->default('aktif');
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};