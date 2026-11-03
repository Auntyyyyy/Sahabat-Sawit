<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'arah',
        'jenis',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim_tujuan',
        'perihal',
        'sifat',
        'status',
        'lampiran',
        'lampiran_nama',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public const ARAH_OPTIONS = ['Masuk', 'Keluar'];

    // Jenis baru (mis. Perjanjian Kerja Sama, Permohonan Cuti) cukup ditambahkan di sini.
    public const JENIS_OPTIONS = [
        'Surat Permohonan',
        'Surat Undangan',
        'Surat Pemberitahuan',
        'Surat Tugas',
        'Surat Pengantar',
        'Surat Perjalanan Dinas',
        'Surat Permintaan Barang',
        'Berita Acara',
        'Lainnya',
    ];

    public const SIFAT_OPTIONS = ['Biasa', 'Penting', 'Segera', 'Rahasia'];

    public const STATUS_OPTIONS = ['Baru', 'Diproses', 'Selesai', 'Diarsipkan'];

        /** Perpindahan status yang bisa dilakukan lewat tombol cepat. */
    public const TRANSITIONS = [
        'Baru'       => ['Diproses', 'Selesai'],
        'Diproses'   => ['Selesai'],
        'Selesai'    => ['Diarsipkan', 'Diproses'],
        'Diarsipkan' => ['Diproses'],
    ];

    public function pihakLabel(): string
    {
        return $this->arah === 'Keluar' ? 'Tujuan' : 'Pengirim';
    }

    // Class untuk komponen .status-pill di layout GO
    public function arahBadgeClass(): string
    {
        return $this->arah === 'Masuk' ? 'aktif' : 'netral';
    }

    public function sifatBadgeClass(): string
    {
        return match ($this->sifat) {
            'Penting' => 'cuti',
            'Segera', 'Rahasia' => 'nonaktif',
            default => 'netral',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'Selesai' => 'aktif',
            'Diproses' => 'cuti',
            default => 'netral',
        };
    }

    public function lampiranEkstensi(): ?string
    {
        return $this->lampiran ? strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION)) : null;
    }

    public function lampiranIsPdf(): bool
    {
        return $this->lampiranEkstensi() === 'pdf';
    }

        public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions(), true);
    }
}