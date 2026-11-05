<?php

namespace App\Services;

use App\Models\Kehadiran;
use App\Models\PengajuanCuti;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Semua aturan pengajuan cuti ada di sini: menghitung hari kerja, menentukan
 * persetujuan otomatis, memeriksa bentrok, serta menyetujui/menolak/membatalkan.
 * Dipakai oleh panel Personalia (mengajukan) dan panel HR (memutuskan).
 */
class CutiService
{
    /**
     * Hari kerja perusahaan: 1 = Senin ... 7 = Minggu.
     * Hari di luar daftar ini tidak dihitung sebagai hari cuti dan tidak dibuatkan catatan kehadiran.
     * Kalau perusahaan bekerja sampai Sabtu, ganti menjadi [1, 2, 3, 4, 5, 6].
     * Catatan: tanggal merah (libur nasional) belum dikenali.
     */
    public const HARI_KERJA = [1, 2, 3, 4, 5];

    /** Jenis cuti yang langsung disetujui, berapa pun jumlah harinya. */
    public const JENIS_OTOMATIS = ['Cuti Sakit'];

    /** Cuti dengan jumlah hari sampai batas ini juga langsung disetujui. */
    public const BATAS_HARI_OTOMATIS = 1;

    /** Pengaman supaya salah ketik tahun tidak menghasilkan ribuan catatan. */
    public const MAKS_RENTANG_HARI = 366;

    /**
     * Daftar hari kerja (objek tanggal) dalam rentang, termasuk tanggal awal dan akhir.
     */
    public function hariCuti(Carbon $mulai, Carbon $selesai): Collection
    {
        $periode = CarbonPeriod::create($mulai->copy()->startOfDay(), $selesai->copy()->startOfDay());

        return collect($periode)
            ->filter(fn ($tgl) => in_array($tgl->dayOfWeekIso, self::HARI_KERJA, true))
            ->values();
    }

    /** Cuti sakit, atau cuti dengan hari sedikit, langsung disetujui tanpa menunggu HR. */
    public function layakOtomatis(string $jenisCuti, int $jumlahHari): bool
    {
        return in_array($jenisCuti, self::JENIS_OTOMATIS, true)
            || $jumlahHari <= self::BATAS_HARI_OTOMATIS;
    }

    /**
     * Periksa apakah pengajuan bentrok dengan data yang sudah ada.
     * Mengembalikan pesan kesalahan, atau null kalau aman.
     */
    public function cekBentrok(int $karyawanId, Collection $hari): ?string
    {
        if ($hari->isEmpty()) {
            return null;
        }

        $sudahAda = $this->tanggalSudahAda($karyawanId, $hari);
        if ($sudahAda->isNotEmpty()) {
            return $this->pesanBentrok($sudahAda);
        }

        $tumpangTindih = PengajuanCuti::where('karyawan_id', $karyawanId)
            ->whereIn('status', [PengajuanCuti::MENUNGGU, PengajuanCuti::DISETUJUI])
            ->whereDate('tanggal_mulai', '<=', $hari->last()->toDateString())
            ->whereDate('tanggal_selesai', '>=', $hari->first()->toDateString())
            ->exists();

        if ($tumpangTindih) {
            return 'Karyawan ini masih punya pengajuan cuti yang tumpang tindih pada rentang tanggal tersebut.';
        }

        return null;
    }

    /**
     * Simpan pengajuan baru. Kalau memenuhi aturan otomatis, langsung disetujui
     * dan catatan kehadirannya dibuat saat itu juga.
     *
     * @throws RuntimeException kalau ternyata ada bentrok saat menyimpan
     */
    public function ajukan(array $data): PengajuanCuti
    {
        $mulai = Carbon::parse($data['tanggal_mulai'])->startOfDay();
        $selesai = Carbon::parse($data['tanggal_selesai'])->startOfDay();
        $jumlahHari = $this->hariCuti($mulai, $selesai)->count();

        return DB::transaction(function () use ($data, $mulai, $selesai, $jumlahHari) {
            $pengajuan = PengajuanCuti::create([
                'karyawan_id' => $data['karyawan_id'],
                'jenis_cuti' => $data['jenis_cuti'],
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_selesai' => $selesai->toDateString(),
                'jumlah_hari' => $jumlahHari,
                'keterangan' => $data['keterangan'] ?? null,
                'status' => PengajuanCuti::MENUNGGU,
            ]);

            if ($this->layakOtomatis($pengajuan->jenis_cuti, $jumlahHari)) {
                return $this->setujui($pengajuan, null, true);
            }

            return $pengajuan;
        });
    }

    /**
     * Setujui pengajuan: buat satu catatan kehadiran berstatus cuti untuk tiap hari kerja.
     * $oleh kosong untuk persetujuan otomatis.
     *
     * @throws RuntimeException kalau sudah diputuskan atau ada bentrok dengan catatan lain
     */
    public function setujui(PengajuanCuti $pengajuan, ?User $oleh = null, bool $otomatis = false): PengajuanCuti
    {
        return DB::transaction(function () use ($pengajuan, $oleh, $otomatis) {
            $p = PengajuanCuti::lockForUpdate()->findOrFail($pengajuan->id);

            if (! $p->sedangMenunggu()) {
                throw new RuntimeException('Pengajuan ini sudah diputuskan sebelumnya.');
            }

            $hari = $this->hariCuti($p->tanggal_mulai, $p->tanggal_selesai);
            if ($hari->isEmpty()) {
                throw new RuntimeException('Rentang tanggal pengajuan ini tidak memuat hari kerja.');
            }

            $sudahAda = $this->tanggalSudahAda($p->karyawan_id, $hari);
            if ($sudahAda->isNotEmpty()) {
                throw new RuntimeException($this->pesanBentrok($sudahAda));
            }

            foreach ($hari as $tgl) {
                Kehadiran::create([
                    'karyawan_id' => $p->karyawan_id,
                    'tanggal' => $tgl->toDateString(),
                    'status' => 'cuti',
                    'jenis_cuti' => $p->jenis_cuti,
                    'pengajuan_cuti_id' => $p->id,
                    'menit_terlambat' => 0,
                    'jam_lembur' => 0,
                ]);
            }

            $p->update([
                'status' => PengajuanCuti::DISETUJUI,
                'otomatis' => $otomatis,
                'diputuskan_oleh' => $oleh?->id,
                'diputuskan_pada' => now(),
            ]);

            return $p;
        });
    }

    /**
     * Tolak pengajuan beserta alasannya. Tidak ada catatan kehadiran yang dibuat.
     *
     * @throws RuntimeException kalau sudah diputuskan
     */
    public function tolak(PengajuanCuti $pengajuan, User $oleh, string $alasan): PengajuanCuti
    {
        return DB::transaction(function () use ($pengajuan, $oleh, $alasan) {
            $p = PengajuanCuti::lockForUpdate()->findOrFail($pengajuan->id);

            if (! $p->sedangMenunggu()) {
                throw new RuntimeException('Pengajuan ini sudah diputuskan sebelumnya.');
            }

            $p->update([
                'status' => PengajuanCuti::DITOLAK,
                'otomatis' => false,
                'diputuskan_oleh' => $oleh->id,
                'diputuskan_pada' => now(),
                'alasan_tolak' => $alasan,
            ]);

            return $p;
        });
    }

    /**
     * Batalkan (hapus) pengajuan yang masih menunggu.
     *
     * @throws RuntimeException kalau sudah diputuskan
     */
    public function batalkan(PengajuanCuti $pengajuan): void
    {
        DB::transaction(function () use ($pengajuan) {
            $p = PengajuanCuti::lockForUpdate()->findOrFail($pengajuan->id);

            if (! $p->sedangMenunggu()) {
                throw new RuntimeException('Hanya pengajuan yang masih menunggu yang bisa dibatalkan.');
            }

            $p->delete();
        });
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================

    /** Tanggal (dari daftar hari) yang sudah punya catatan kehadiran untuk karyawan ini. */
    protected function tanggalSudahAda(int $karyawanId, Collection $hari): Collection
    {
        return Kehadiran::where('karyawan_id', $karyawanId)
            ->whereIn('tanggal', $hari->map(fn ($t) => $t->toDateString())->all())
            ->orderBy('tanggal')
            ->pluck('tanggal')
            ->map(fn ($t) => Carbon::parse($t));
    }

    protected function pesanBentrok(Collection $tanggal): string
    {
        $daftar = $tanggal->take(5)
            ->map(fn ($t) => $t->copy()->locale('id')->translatedFormat('d M'))
            ->implode(', ');

        $lebih = $tanggal->count() - 5;

        return 'Karyawan ini sudah punya catatan kehadiran pada ' . $daftar
            . ($lebih > 0 ? " dan {$lebih} tanggal lain" : '')
            . '. Hapus atau ubah catatan itu dulu sebelum mengajukan cuti.';
    }
}