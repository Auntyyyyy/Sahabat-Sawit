<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FacilityLoan extends Model
{
    protected $fillable = [
        'nomor', 'facility_id', 'peminjam_nama', 'peminjam_divisi',
        'tanggal_mulai', 'tanggal_selesai', 'jumlah_orang', 'keperluan',
        'status', 'catatan', 'tanggal_kembali', 'kondisi_kembali', 'catatan_kembali',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public const STATUS_OPTIONS = ['Diajukan', 'Disetujui', 'Sedang Digunakan', 'Selesai', 'Ditolak', 'Dibatalkan'];

    /** Perpindahan status yang diizinkan. */
    public const TRANSITIONS = [
        'Diajukan'         => ['Disetujui', 'Ditolak', 'Dibatalkan'],
        'Disetujui'        => ['Sedang Digunakan', 'Dibatalkan'],
        'Sedang Digunakan' => ['Selesai'],
        'Selesai'          => [],
        'Ditolak'          => [],
        'Dibatalkan'       => [],
    ];

    /** Status yang "memegang" jadwal fasilitas (dipakai untuk cek bentrok). */
    public const JADWAL_AKTIF = ['Disetujui', 'Sedang Digunakan'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /** Nomor otomatis, mis. PJM-202611-0001 (urut per bulan). */
    public static function generateNomor(): string
    {
        $prefix = 'PJM-' . now()->format('Ym') . '-';

        $last = static::where('nomor', 'like', $prefix . '%')->orderByDesc('nomor')->value('nomor');
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /** Class .status-pill di layout GO. */
    public function statusPillClass(): string
    {
        return match ($this->status) {
            'Diajukan'         => 'cuti',
            'Disetujui'        => 'info',
            'Sedang Digunakan' => 'aktif',
            'Selesai'          => 'netral',
            default            => 'nonaktif', // Ditolak, Dibatalkan
        };
    }

    public function kondisiKembaliPillClass(): string
    {
        return match ($this->kondisi_kembali) {
            'Baik' => 'aktif',
            'Rusak Ringan', 'Dalam Perbaikan' => 'cuti',
            default => 'nonaktif',
        };
    }

    public function durasiHari(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions(), true);
    }

    /** Peminjaman lain (Disetujui / Sedang Digunakan) yang tanggalnya beririsan di fasilitas yang sama. */
    public function conflicts()
    {
        return static::query()
            ->where('facility_id', $this->facility_id)
            ->where('id', '!=', $this->id)
            ->whereIn('status', self::JADWAL_AKTIF)
            ->whereDate('tanggal_mulai', '<=', $this->tanggal_selesai->toDateString())
            ->whereDate('tanggal_selesai', '>=', $this->tanggal_mulai->toDateString())
            ->orderBy('tanggal_mulai')
            ->get();
    }

    /**
     * Ubah status (selain "Selesai", yang lewat complete()).
     *
     * @throws \RuntimeException
     */
    public function transitionTo(string $newStatus): void
    {
        if (! $this->canTransitionTo($newStatus)) {
            throw new \RuntimeException("Status tidak bisa diubah dari {$this->status} ke {$newStatus}.");
        }

        DB::transaction(function () use ($newStatus) {
            // Kunci baris fasilitas supaya dua persetujuan tidak lolos bersamaan
            Facility::whereKey($this->facility_id)->lockForUpdate()->first();

            if ($newStatus === 'Disetujui') {
                $bentrok = $this->conflicts()->first();

                if ($bentrok) {
                    $mulai = $bentrok->tanggal_mulai->translatedFormat('d M Y');
                    $selesai = $bentrok->tanggal_selesai->translatedFormat('d M Y');

                    throw new \RuntimeException(
                        "Jadwal bentrok dengan {$bentrok->nomor} ({$bentrok->peminjam_nama}, {$mulai} – {$selesai})."
                    );
                }
            }

            if ($newStatus === 'Sedang Digunakan') {
                $dipakai = static::where('facility_id', $this->facility_id)
                    ->where('id', '!=', $this->id)
                    ->where('status', 'Sedang Digunakan')
                    ->first();

                if ($dipakai) {
                    throw new \RuntimeException(
                        "Fasilitas masih digunakan oleh {$dipakai->nomor} ({$dipakai->peminjam_nama}). Selesaikan pengembaliannya dulu."
                    );
                }
            }

            $this->update(['status' => $newStatus]);
        });
    }

    /**
     * Catat pengembalian. Kalau kondisi saat dikembalikan bukan "Baik",
     * kondisi fasilitas ikut diperbarui otomatis.
     *
     * @throws \RuntimeException
     */
    public function complete(array $data): void
    {
        if (! $this->canTransitionTo('Selesai')) {
            throw new \RuntimeException("Status tidak bisa diubah dari {$this->status} ke Selesai.");
        }

        DB::transaction(function () use ($data) {
            $this->update([
                'status' => 'Selesai',
                'tanggal_kembali' => $data['tanggal_kembali'],
                'kondisi_kembali' => $data['kondisi_kembali'],
                'catatan_kembali' => $data['catatan_kembali'] ?? null,
            ]);

            if ($data['kondisi_kembali'] !== 'Baik') {
                $this->facility->update(['kondisi' => $data['kondisi_kembali']]);
            }
        });
    }
}