<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AtkRequest extends Model
{
    protected $fillable = [
        'nomor', 'karyawan_id', 'pemohon_nama', 'pemohon_divisi', 'tanggal', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public const STATUS_OPTIONS = ['Diajukan', 'Disetujui', 'Diserahkan', 'Ditolak', 'Dibatalkan'];

    /** Perpindahan status yang diizinkan. */
    public const TRANSITIONS = [
        'Diajukan'   => ['Disetujui', 'Ditolak'],
        'Disetujui'  => ['Diserahkan', 'Ditolak'],
        'Diserahkan' => ['Dibatalkan'],
        'Ditolak'    => [],
        'Dibatalkan' => [],
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function items()
    {
        return $this->hasMany(AtkRequestItem::class);
    }

    /** Nomor otomatis, mis. ATK-202610-0001 (urut per bulan). */
    public static function generateNomor(): string
    {
        $prefix = 'ATK-' . now()->format('Ym') . '-';

        $last = static::where('nomor', 'like', $prefix . '%')->orderByDesc('nomor')->value('nomor');
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /** Class .status-pill di layout GO. */
    public function statusPillClass(): string
    {
        return match ($this->status) {
            'Diajukan'   => 'cuti',
            'Disetujui'  => 'info',
            'Diserahkan' => 'aktif',
            default      => 'nonaktif', // Ditolak, Dibatalkan
        };
    }

    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions(), true);
    }

    /**
     * Ubah status. Stok berkurang saat menjadi "Diserahkan" dan dikembalikan
     * saat "Diserahkan" dibatalkan. Semuanya dalam satu transaksi: kalau stok
     * satu barang saja tidak cukup, tidak ada stok yang berubah sama sekali.
     *
     * @throws \RuntimeException
     */
    public function transitionTo(string $newStatus): void
    {
        if (! $this->canTransitionTo($newStatus)) {
            throw new \RuntimeException("Status tidak bisa diubah dari {$this->status} ke {$newStatus}.");
        }

        DB::transaction(function () use ($newStatus) {
            $rows = $this->items()->get();

            if ($newStatus === 'Diserahkan') {
                foreach ($rows as $row) {
                    $item = AtkItem::whereKey($row->atk_item_id)->lockForUpdate()->first();

                    if (! $item || $item->stok < $row->jumlah) {
                        $sisa = $item?->stok ?? 0;
                        $nama = $item?->nama ?? 'barang';
                        throw new \RuntimeException(
                            "Stok {$nama} tidak cukup (tersisa {$sisa}, diminta {$row->jumlah}). Catat barang masuk terlebih dahulu."
                        );
                    }

                    $item->decrement('stok', $row->jumlah);
                }
            }

            if ($newStatus === 'Dibatalkan' && $this->status === 'Diserahkan') {
                foreach ($rows as $row) {
                    AtkItem::whereKey($row->atk_item_id)->increment('stok', $row->jumlah);
                }
            }

            $this->update(['status' => $newStatus]);
        });
    }
}