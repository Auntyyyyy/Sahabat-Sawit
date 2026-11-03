<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master divisi (dipakai sebagai saran di form)
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // Pengaturan GA berbentuk kunci-nilai
        Schema::create('ga_settings', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->string('nilai')->nullable();
            $table->timestamps();
        });

        // Impor divisi yang sudah pernah tercatat supaya daftarnya tidak kosong
        $names = collect()
            ->merge(DB::table('atk_requests')->whereNotNull('pemohon_divisi')->pluck('pemohon_divisi'))
            ->merge(DB::table('facility_loans')->whereNotNull('peminjam_divisi')->pluck('peminjam_divisi'))
            ->merge(DB::table('karyawans')->whereNotNull('division')->pluck('division'))
            ->map(fn ($n) => trim((string) $n))
            ->filter()
            ->unique(fn ($n) => mb_strtolower($n))
            ->values();

        foreach ($names as $nama) {
            DB::table('divisions')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ga_settings');
        Schema::dropIfExists('divisions');
    }
};