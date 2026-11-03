<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    protected $table = 'dokumen_kepegawaian';

    protected $fillable = [
        'karyawan_id',
        'judul',
        'kategori',
        'keterangan',
        'berkas_path',
        'nama_asli',
        'ukuran',
        'tipe_mime',
        'tanggal_kedaluwarsa',
        'diunggah_oleh',
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'ukuran' => 'integer',
    ];

    // Kategori dokumen milik karyawan. Mau menambah kategori? Cukup tambahkan di sini.
    public const KATEGORI_KARYAWAN = [
        'KTP',
        'Kartu Keluarga',
        'NPWP',
        'BPJS',
        'Ijazah',
        'CV',
        'Sertifikat',
        'Surat Keputusan',
        'Surat Peringatan',
        'Lainnya',
    ];

    // Kategori dokumen perusahaan.
    public const KATEGORI_PERUSAHAAN = [
        'SOP',
        'Kebijakan',
        'Peraturan Perusahaan',
        'Formulir',
        'Surat Edaran',
        'Lainnya',
    ];

    /** Berapa hari sebelum kedaluwarsa dokumen ditandai "Segera berakhir". */
    public const HARI_PERINGATAN = 30;

    /** Jenis file yang boleh dibuka langsung di browser (selain itu hanya bisa diunduh). */
    public const TIPE_BISA_DILIHAT = ['application/pdf', 'image/jpeg', 'image/png'];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diunggah_oleh');
    }

    /** Dokumen perusahaan = tidak terkait karyawan mana pun. */
    public function getIsPerusahaanAttribute(): bool
    {
        return $this->karyawan_id === null;
    }

    /** Status masa berlaku: kode dan label untuk ditampilkan. */
    public function getStatusBerlakuAttribute(): array
    {
        if (! $this->tanggal_kedaluwarsa) {
            return ['kode' => 'tanpa', 'label' => 'Tanpa batas'];
        }

        if ($this->tanggal_kedaluwarsa->lt(today())) {
            return ['kode' => 'kedaluwarsa', 'label' => 'Kedaluwarsa'];
        }

        if ($this->tanggal_kedaluwarsa->lte(today()->addDays(self::HARI_PERINGATAN))) {
            return ['kode' => 'segera', 'label' => 'Segera berakhir'];
        }

        return ['kode' => 'berlaku', 'label' => 'Berlaku'];
    }

    public function getBisaDilihatAttribute(): bool
    {
        return in_array($this->tipe_mime, self::TIPE_BISA_DILIHAT, true);
    }

    public function getUkuranTerbacaAttribute(): string
    {
        $byte = (int) $this->ukuran;

        if ($byte >= 1048576) {
            return number_format($byte / 1048576, 1, ',', '.') . ' MB';
        }

        return number_format(max($byte, 1) / 1024, 0, ',', '.') . ' KB';
    }

    public function getEkstensiAttribute(): string
    {
        return strtoupper(pathinfo((string) $this->nama_asli, PATHINFO_EXTENSION)) ?: 'FILE';
    }

    /** File fisik ikut dihapus saat dokumen dihapus lewat Eloquent. */
    protected static function booted(): void
    {
        static::deleting(function (Dokumen $dokumen) {
            if ($dokumen->berkas_path) {
                Storage::disk('local')->delete($dokumen->berkas_path);
            }
        });
    }
}