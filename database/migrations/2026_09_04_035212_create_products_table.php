<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // CPO, Kernel, Cangkang, Fiber
            $table->text('description')->nullable();
            $table->string('raw_material')->nullable();   // Bahan Baku
            $table->string('product_form')->nullable();   // Bentuk
            $table->string('usage')->nullable();           // Pemanfaatan
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};