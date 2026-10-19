<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use Illuminate\Database\Seeder;

// Data CONTOH untuk uji tampilan. Hapus atau ganti dengan lowongan asli sebelum website dipublikasikan.
class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        Lowongan::updateOrCreate(
            ['slug' => 'staf-administrasi-contoh'],
            [
                'judul'     => 'Staf Administrasi (Contoh)',
                'lokasi'    => 'Kantor Pusat, Medan',
                'deskripsi' => "Minimal pendidikan D3 di bidang Administrasi atau bidang terkait.\nMemiliki minimal 1 tahun pengalaman kerja yang relevan.\nMenguasai Microsoft Office.\nTeliti, disiplin, dan mampu bekerja dalam tim.\nBersedia mengikuti proses seleksi secara langsung.",
                'is_active' => true,
                'order'     => 1,
            ]
        );
    }
}