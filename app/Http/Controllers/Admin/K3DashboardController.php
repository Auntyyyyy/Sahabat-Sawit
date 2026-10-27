<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Dokumen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/**
 * Dashboard K3. SEMUA angka diambil dari database, tidak ada data contoh.
 *
 * Tiap modul punya tabelnya sendiri. Kalau tabel sebuah modul belum dibuat,
 * angkanya tampil 0 (tidak error). Begitu modulnya dibuat dan datanya ditambahkan,
 * angka di dashboard berubah sendiri, tanpa perlu mengubah file ini lagi.
 *
 * Tabel dan kolom yang dibaca dashboard ini:
 *
 *  k3_dokumen     : status ('aktif'/'arsip'), tanggal, judul, kategori, file
 *  k3_peralatan   : kategori ('APD'/'APAR'/'P3K'), nama, lokasi, jumlah, tanggal_kedaluwarsa
 *  k3_patrol      : tanggal, lokasi, uraian, hasil ('sesuai'/'temuan'), status_tindak ('terbuka'/'selesai')
 *  k3_pengukuran  : tanggal, titik, parameter, nilai
 *  k3_program     : nama, status ('rencana'/'berjalan'/'selesai'), target_tanggal
 *  k3_sertifikat  : nama, nomor, tanggal_kedaluwarsa
 *  k3_agenda      : judul, pic, tanggal, waktu
 *  k3_pengaturan  : kunci, nilai   (kunci 'lti_terakhir' = tanggal insiden LTI terakhir)
 */
class K3DashboardController extends Controller
{
    // Semua perhitungan tanggal dan sapaan memakai waktu Indonesia Barat
    private const TZ = 'Asia/Jakarta';

    // Batas hari untuk peringatan "perlu perhatian"
    private const BATAS_APAR_HARI = 90;
    private const BATAS_SERTIFIKAT_HARI = 60;

    // Dashboard K3 — GET /k3/dashboard
    public function index(): View
    {
        $hariIni = now(self::TZ)->startOfDay();

        // ===== Dokumen Keselamatan =====
        $dokumenAktif = 0;
        $dokumenTerbaru = collect();

        if (Schema::hasTable('k3_dokumen')) {
            $dokumenAktif = K3Dokumen::aktif()->count();
            $dokumenTerbaru = K3Dokumen::orderByDesc('tanggal')->orderByDesc('id')->take(5)->get();
        }

        // ===== Peralatan (APD, APAR, P3K) =====
        $apdJenis = $apdStock = $aparTotal = $aparRefill = $p3kArea = $p3kItem = 0;

        if (Schema::hasTable('k3_peralatan')) {
            $batasApar = $hariIni->copy()->addDays(self::BATAS_APAR_HARI)->toDateString();

            $apdJenis   = DB::table('k3_peralatan')->where('kategori', 'APD')->count();
            $apdStock   = (int) DB::table('k3_peralatan')->where('kategori', 'APD')->sum('jumlah');
            $aparTotal  = (int) DB::table('k3_peralatan')->where('kategori', 'APAR')->sum('jumlah');
            $aparRefill = (int) DB::table('k3_peralatan')
                ->where('kategori', 'APAR')
                ->whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '<=', $batasApar)
                ->sum('jumlah');
            $p3kArea    = DB::table('k3_peralatan')->where('kategori', 'P3K')->whereNotNull('lokasi')->distinct()->count('lokasi');
            $p3kItem    = (int) DB::table('k3_peralatan')->where('kategori', 'P3K')->sum('jumlah');
        }

        $aparSiapPersen = $aparTotal > 0
            ? (int) round(max(0, $aparTotal - $aparRefill) / $aparTotal * 100)
            : 0;

        // ===== Safety Patrol =====
        $patrolItem = $patrolSesuai = $temuanOpen = 0;

        if (Schema::hasTable('k3_patrol')) {
            $patrolItem   = DB::table('k3_patrol')->count();
            $patrolSesuai = DB::table('k3_patrol')->where('hasil', 'sesuai')->count();
            $temuanOpen   = DB::table('k3_patrol')->where('hasil', 'temuan')->where('status_tindak', 'terbuka')->count();
        }

        $patrolPersen = $patrolItem > 0 ? (int) round($patrolSesuai / $patrolItem * 100) : 0;

        // ===== Pengukuran Lingkungan =====
        $pengukuranTotal = $titikPengukuran = 0;

        if (Schema::hasTable('k3_pengukuran')) {
            $pengukuranTotal = DB::table('k3_pengukuran')->count();
            $titikPengukuran = DB::table('k3_pengukuran')->distinct()->count('titik');
        }

        // ===== Program K3L =====
        $programTotal = $programSelesai = 0;

        if (Schema::hasTable('k3_program')) {
            $programTotal   = DB::table('k3_program')->count();
            $programSelesai = DB::table('k3_program')->where('status', 'selesai')->count();
        }

        $programPersen = $programTotal > 0 ? (int) round($programSelesai / $programTotal * 100) : 0;

        // ===== Sertifikat hampir kedaluwarsa (termasuk yang sudah lewat) =====
        $sertifikatHampir = 0;

        if (Schema::hasTable('k3_sertifikat')) {
            $batasSertifikat = $hariIni->copy()->addDays(self::BATAS_SERTIFIKAT_HARI)->toDateString();
            $sertifikatHampir = DB::table('k3_sertifikat')
                ->whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '<=', $batasSertifikat)
                ->count();
        }

        // ===== Agenda (3 kegiatan terdekat yang belum lewat) =====
        $agenda = [];

        if (Schema::hasTable('k3_agenda')) {
            $agenda = DB::table('k3_agenda')
                ->whereDate('tanggal', '>=', $hariIni->toDateString())
                ->orderBy('tanggal')
                ->limit(3)
                ->get()
                ->map(fn ($r) => [
                    'judul'   => $r->judul,
                    'pic'     => $r->pic ?: '-',
                    'tanggal' => Carbon::parse($r->tanggal)->locale('id')->translatedFormat('d M Y'),
                    'waktu'   => $r->waktu ?: '-',
                ])
                ->all();
        }

        // ===== Jam Kerja Aman: dihitung sejak tanggal insiden LTI terakhir =====
        $safeSince = null;

        if (Schema::hasTable('k3_pengaturan')) {
            $nilai = DB::table('k3_pengaturan')->where('kunci', 'lti_terakhir')->value('nilai');

            if ($nilai) {
                try {
                    $safeSince = Carbon::parse($nilai, self::TZ)->toIso8601String();
                } catch (\Throwable $e) {
                    $safeSince = null;
                }
            }
        }

        // ===== Daftar modul yang tabelnya belum ada (untuk catatan di dashboard) =====
        $semuaModul = [
            'k3_dokumen'    => 'Dokumen Keselamatan',
            'k3_peralatan'  => 'Peralatan',
            'k3_patrol'     => 'Safety Patrol',
            'k3_pengukuran' => 'Pengukuran Lingkungan',
            'k3_program'    => 'Program K3L',
            'k3_sertifikat' => 'Sertifikat',
            'k3_agenda'     => 'Agenda',
            'k3_pengaturan' => 'Pengaturan Jam Kerja Aman',
        ];

        $modulBelumAktif = [];
        foreach ($semuaModul as $tabel => $label) {
            if (! Schema::hasTable($tabel)) {
                $modulBelumAktif[] = $label;
            }
        }

        $stats = [
            'dokumen_aktif'     => $dokumenAktif,

            'pengukuran_total'  => $pengukuranTotal,
            'titik_pengukuran'  => $titikPengukuran,

            'safe_since'        => $safeSince,

            'apd_jenis'         => $apdJenis,
            'apd_stock'         => $apdStock,
            'apar_total'        => $aparTotal,
            'apar_refill'       => $aparRefill,
            'apar_siap_persen'  => $aparSiapPersen,
            'p3k_area'          => $p3kArea,
            'p3k_item'          => $p3kItem,

            'program_total'     => $programTotal,
            'program_selesai'   => $programSelesai,
            'program_rencana'   => $programTotal - $programSelesai,
            'program_persen'    => $programPersen,

            'patrol_item'       => $patrolItem,
            'patrol_sesuai'     => $patrolPersen,

            'temuan_open'       => $temuanOpen,
            'sertifikat_hampir' => $sertifikatHampir,
        ];

        // Sapaan sesuai jam WIB
        $jam = (int) now(self::TZ)->format('G');
        $sapaan = match (true) {
            $jam < 11 => 'Selamat pagi',
            $jam < 15 => 'Selamat siang',
            $jam < 18 => 'Selamat sore',
            default   => 'Selamat malam',
        };

        return view('admin.k3.dashboard', [
            'stats'           => $stats,
            'agenda'          => $agenda,
            'dokumenTerbaru'  => $dokumenTerbaru,
            'sapaan'          => $sapaan,
            'tanggalHariIni'  => now(self::TZ)->locale('id')->translatedFormat('l, d F Y'),
            'modulBelumAktif' => $modulBelumAktif,
            // Tombol/tautan ke modul dokumen hanya ditampilkan kalau route-nya sudah ada
            'bisaDokumen'     => Route::has('k3.dokumen.index'),
        ]);
    }
}