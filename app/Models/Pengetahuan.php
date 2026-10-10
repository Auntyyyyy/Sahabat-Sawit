<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengetahuan extends Model
{
    protected $table = 'pengetahuan';

    protected $fillable = ['judul', 'slug', 'kategori_id', 'gambar', 'ringkasan', 'order'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function kategoriData(): BelongsTo
    {
        return $this->belongsTo(PengetahuanKategori::class, 'kategori_id');
    }

    // Penjelasan dalam bentuk HTML (dipakai di halaman detail).
    // Data lama yang berupa teks biasa tetap tampil rapi, baris barunya dipertahankan.
    public function getRingkasanHtmlAttribute(): string
    {
        $isi = (string) $this->ringkasan;

        if ($isi === strip_tags($isi)) {
            return nl2br(e($isi));
        }

        return $isi;
    }

    // Penjelasan dalam teks polos tanpa tag HTML (dipakai di kartu dan daftar admin)
    public function getRingkasanTeksAttribute(): string
    {
        $isi = (string) $this->ringkasan;
        $isi = preg_replace('/<\/(p|h[1-6]|li|div)>|<br\s*\/?>/i', ' $0', $isi);
        $teks = html_entity_decode(strip_tags($isi), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/', ' ', $teks));
    }
}