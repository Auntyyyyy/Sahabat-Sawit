<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration ini TIDAK mengubah struktur kolom (kolom 'status' kamu sudah
 * berupa string biasa, bukan ENUM di level database — jadi aman diisi
 * nilai apa pun, validasinya ada di level aplikasi/Controller).
 *
 * Yang diubah di sini cuma DATA yang sudah ada, supaya cocok dengan
 * pilihan status yang baru:
 *   - 'aktif'    tetap 'aktif'
 *   - 'cuti'     dipetakan ke 'aktif' (anggap masih bekerja, sedang cuti)
 *   - 'nonaktif' dipetakan ke 'resign'
 *
 * CATATAN PENTING: pemetaan 'cuti' -> 'aktif' dan 'nonaktif' -> 'resign'
 * ini ASUMSI saya karena tidak ada padanan 1:1 yang pasti (status lama
 * soal keaktifan, status baru mencampur jenis kontrak kerja + keaktifan).
 * Kalau asumsi ini tidak sesuai kondisi karyawan kamu yang sebenarnya,
 * jalankan migration ini, lalu edit manual data yang perlu dikoreksi
 * lewat halaman admin (atau kasih tahu saya, saya sesuaikan query-nya).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('karyawans')->where('status', 'cuti')->update(['status' => 'aktif']);
        DB::table('karyawans')->where('status', 'nonaktif')->update(['status' => 'resign']);
    }

    public function down(): void
    {
        // Data lama tidak bisa dikembalikan persis (informasi "cuti" sudah
        // tergabung ke "aktif"), jadi rollback ini hanya best-effort.
        DB::table('karyawans')->where('status', 'resign')->update(['status' => 'nonaktif']);
    }
};