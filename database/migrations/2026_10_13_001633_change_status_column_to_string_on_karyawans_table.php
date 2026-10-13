<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Kolom 'status' di tabel karyawans ternyata ENUM asli di database
 * (bukan VARCHAR seperti asumsi migration remap sebelumnya), dengan
 * daftar nilai lama ('aktif','cuti','nonaktif'). Itu sebabnya insert
 * dengan nilai baru ('kontrak','trainee','resign') gagal — MySQL
 * menolak nilai yang tidak ada di definisi ENUM-nya.
 *
 * Migration ini mengubah kolom jadi VARCHAR biasa, supaya validasi
 * nilai yang diperbolehkan cukup diatur di Controller (validateData())
 * seperti sekarang — tidak perlu ubah struktur tabel lagi tiap kali
 * daftar status berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE karyawans MODIFY status VARCHAR(20) NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE karyawans MODIFY status ENUM('aktif','cuti','nonaktif') NOT NULL DEFAULT 'aktif'");
    }
};