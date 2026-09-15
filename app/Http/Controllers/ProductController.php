<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // processSteps tetap statis karena bukan bagian dari manajemen produk (biarkan seperti semula)
    private array $processSteps = [
        [
            'step'  => '01',
            'label' => 'Lokasi PKS (Pabrik Kelapa Sawit) untuk proses pengolahan TBS (Tandan Buah Segar).',
            'image' => 'herotentang.png',
            'alt'   => 'lokasi PKS',
        ],
        [
            'step'  => '02',
            'label' => 'Proses penimbangan (Weighbridge) TBS (Tandah Buah Segar).',
            'image' => 'prosesangkut.png',
            'alt'   => 'proses penimbangan',
        ],
        [
            'step'  => '03',
            'label' => 'proses penyortiran dan Grading TBS (Tandan Buah Segar).',
            'image' => 'sortasi',
            'alt'   => 'proses penyortiran',
        ],
        [
            'step'  => '04',
            'label' => 'Kontrol Mutu',
            'image' => 'perebusan.png',
            'alt'   => 'Kontrol Mutu',
        ],
        [
            'step'  => '05',
            'label' => 'Proses pengambilan sampel Kernel, Fiber, dan cangkang.',
            'image' => 'sampel1.jpeg',
            'alt'   => 'Produk Siap Distribusi',
        ],
        [
            'step'  => '06',
            'label' => 'Pengawasan Stasiun Sterilizer (Sterilization Station).',
            'image' => 'stasiunrebus.jpeg',
            'alt'   => 'Produk Siap Distribusi',
        ],
        [
            'step'  => '07',
            'label' => 'Pengawasan kinerja boiler.',
            'image' => 'pengawasboiler.png',
            'alt'   => 'Pengawasan Boiler',
        ],
    ];

    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('products', [
            'products' => $products,
            'processSteps' => $this->processSteps,
        ]);
    }

    public function show($slug)
    {
        $products = Product::where('is_active', true)
            ->orderBy('order')
            ->get();

        $index = $products->search(fn ($p) => $p->slug === $slug);

        if ($index === false) {
            abort(404);
        }

        $total = $products->count();

        return view('product-detail', [
            'product'     => $products[$index],
            'prevProduct' => $products[($index - 1 + $total) % $total],
            'nextProduct' => $products[($index + 1) % $total],
        ]);
    }
}