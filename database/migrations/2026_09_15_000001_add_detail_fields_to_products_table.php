<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('products', 'detail')) {
                $table->text('detail')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'manfaat')) {
                $table->json('manfaat')->nullable()->after('usage');
            }
            if (!Schema::hasColumn('products', 'spesifikasi')) {
                $table->json('spesifikasi')->nullable()->after('manfaat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'detail', 'manfaat', 'spesifikasi']);
        });
    }
};
