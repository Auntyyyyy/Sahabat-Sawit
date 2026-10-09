<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // Daftar berita — GET /media/berita
    public function index()
    {
        $beritas = Berita::orderByDesc('tanggal')->paginate(9);

        return view('media.berita', compact('beritas'));
    }

    // Detail berita — GET /media/berita/{berita:slug}
    public function show(Berita $berita)
    {
        // Hitung jumlah dilihat: satu pengunjung (satu sesi browser) hanya dihitung
        // sekali per berita, jadi menekan refresh berulang kali tidak menambah angka.
        $sessionKey = 'berita_viewed_' . $berita->id;

        if (! session()->has($sessionKey)) {
            // toBase() supaya hanya kolom views yang berubah (updated_at berita tidak ikut berubah)
            Berita::whereKey($berita->id)->toBase()->increment('views');
            session()->put($sessionKey, true);
            $berita->views = $berita->views + 1; // untuk tampilan di halaman ini saja
        }

        // 3 berita terbaru lainnya (berita yang sedang dibuka tidak ikut ditampilkan)
        $beritaLain = Berita::where('id', '!=', $berita->id)
            ->orderByDesc('tanggal')
            ->take(3)
            ->get();

        return view('media.berita-detail', compact('berita', 'beritaLain'));
    }
}