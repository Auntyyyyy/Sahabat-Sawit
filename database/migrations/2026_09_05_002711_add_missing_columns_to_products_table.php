<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
        if (!Schema::hasColumn('products', 'description')) {
            $table->text('description')->nullable();
        }
        if (!Schema::hasColumn('products', 'raw_material')) {
            $table->string('raw_material')->nullable();
        }
        if (!Schema::hasColumn('products', 'product_form')) {
            $table->string('product_form')->nullable();
        }
        if (!Schema::hasColumn('products', 'usage')) {
            $table->string('usage')->nullable();
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['description', 'raw_material', 'product_form', 'usage']);
        });
    }
};
