<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            // Menghubungkan baris cuti di kehadirans dengan pengajuan yang menghasilkannya.
            // Kosong untuk catatan cuti lama (sebelum ada pengajuan), yang dianggap sudah disetujui.
            $table->foreignId('pengajuan_cuti_id')
                ->nullable()
                ->after('jenis_cuti')
                ->constrained('pengajuan_cutis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_cuti_id']);
            $table->dropColumn('pengajuan_cuti_id');
        });
    }
};