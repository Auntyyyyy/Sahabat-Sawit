@extends('layouts.app')

@section('title', 'Profil Manajemen — PT Sahabat Sawit')

@section('content')
@include('about._style')

{{--
    Struktur organisasi per level, dalam kartu bertingkat.
    - Foto belum ada  : tampil siluet (pria / wanita berambut sanggul).
    - Penanda wanita  : tambahkan 'jk' => 'p' pada anggota di AboutController (tanpa 'jk' dianggap pria).
    - Foto asli       : tambahkan 'foto' => 'nama-file.jpg' (file di public/images/struktur/), otomatis menggantikan siluet.
--}}
@php
    $orgLevelLabels = [
        1 => 'Pimpinan Tertinggi',
        2 => 'Direksi',
        3 => 'Manajer',
        4 => 'Kepala & Supervisor',
        5 => 'Officer & Staf',
    ];

    $orgLevels = collect($organisasi)->groupBy('level')->sortKeys();

    // Tiga bagian halaman: pimpinan (gelap), manajer + supervisor (terang, sebagai jeda), officer & staf (gelap)
    $bagian = [
        ['class' => 'ab-sec so-top',        'grid' => 'so-groups--lead', 'levels' => $orgLevels->only([1, 2])],
        ['class' => 'so-band so-light',     'grid' => '',                'levels' => $orgLevels->only([3, 4])],
        ['class' => 'so-band so-dark',      'grid' => '',                'levels' => $orgLevels->filter(fn ($v, $k) => $k >= 5)],
    ];
@endphp

<style>
    /* Struktur organisasi — kartu bertingkat. Class berawalan "so-". */

    /* Sprite siluet (disembunyikan, dipakai lewat <use>) */
    .so-sprite { position: absolute; width: 0; height: 0; overflow: hidden; }

    .so-band { --so-gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(3.5rem, 8vh, 5.5rem) var(--so-gutter); }
    .so-dark { border-top: 1px solid rgba(242,244,236,.12); }
    .so-light { background: #f2f4ec; color: #16321f; }

    .so-groups { margin-top: clamp(2.5rem, 6vh, 4rem); }
    .so-band .so-groups { margin-top: 0; }
    .so-groups--lead { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2.5rem 2rem; }
    @media (min-width: 900px) { .so-groups--lead { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .so-group { min-width: 0; }
    .so-group + .so-group { margin-top: clamp(2.25rem, 5vh, 3.25rem); }
    .so-groups--lead .so-group + .so-group { margin-top: 0; }

    /* Judul kelompok: nama + jumlah + garis tipis */
    .so-head { display: flex; align-items: center; gap: .75rem; margin: 0 0 1.1rem; font-size: .95rem; font-weight: 600; color: rgba(242,244,236,.8); }
    .so-head::after { content: ''; flex: 1; height: 1px; background: rgba(242,244,236,.18); }
    .so-count { font-weight: 400; color: rgba(242,244,236,.5); }
    .so-light .so-head { color: #16321f; }
    .so-light .so-head::after { background: rgba(22,50,31,.2); }
    .so-light .so-count { color: rgba(22,50,31,.55); }

    /* Kartu */
    .so-cards { list-style: none; margin: 0; padding: 0; display: grid; gap: .9rem; }
    .so-cards--lead { grid-template-columns: minmax(0, 1fr); }
    .so-cards--lv3 { grid-template-columns: repeat(auto-fill, minmax(min(100%, 17rem), 1fr)); }
    .so-cards--lv4 { grid-template-columns: repeat(auto-fill, minmax(min(100%, 16rem), 1fr)); }
    .so-cards--lv5 { grid-template-columns: repeat(auto-fill, minmax(min(100%, 15rem), 1fr)); gap: .75rem; }

    .so-card { display: flex; align-items: center; gap: 1rem; min-width: 0; padding: 1rem; border-radius: .5rem; background: rgba(242,244,236,.06); border: 1px solid rgba(242,244,236,.12); transition: transform .25s, box-shadow .25s, border-color .25s, background .25s; }
    .so-light .so-card { background: #fff; border-color: rgba(22,50,31,.12); }
    .so-card--lead { --av: clamp(5.5rem, 11vw, 8.5rem); gap: 1.5rem; padding: 1.25rem; border-left: 3px solid #e3a72f; }
    .so-card--lv3 { --av: 4.75rem; }
    .so-card--lv4 { --av: 4rem; padding: .85rem 1rem; }
    .so-card--lv5 { --av: 3.25rem; padding: .7rem .9rem; gap: .85rem; }

    /* Avatar: siluet bawaan, foto asli menimpa bila ada */
    .so-av { position: relative; flex: none; width: var(--av); height: var(--av); overflow: hidden; border-radius: 50%; background: linear-gradient(160deg, #3a6b47, #1d4129); }
    .so-card--lead .so-av { border-radius: .5rem; }
    .so-av svg, .so-av img { position: absolute; inset: 0; display: block; width: 100%; height: 100%; }
    .so-av img { object-fit: cover; }

    .so-info { min-width: 0; }
    .so-name { font-weight: 700; letter-spacing: -.02em; line-height: 1.2; overflow-wrap: break-word; }
    .so-role { margin-top: .25rem; line-height: 1.35; color: rgba(242,244,236,.68); overflow-wrap: break-word; }
    .so-light .so-role { color: rgba(22,50,31,.68); }

    .so-card--lead .so-name { font-size: clamp(1.4rem, 2.6vw, 2rem); }
    .so-card--lead .so-role { font-size: 1rem; font-weight: 600; color: #e3a72f; }
    .so-card--lv3 .so-name { font-size: 1.15rem; }
    .so-card--lv3 .so-role { font-size: .9rem; }
    .so-card--lv4 .so-name { font-size: 1.05rem; }
    .so-card--lv4 .so-role { font-size: .88rem; }
    .so-card--lv5 .so-name { font-size: 1rem; }
    .so-card--lv5 .so-role { font-size: .85rem; }

    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .so-card:hover { transform: translateY(-3px); background: rgba(242,244,236,.1); border-color: rgba(227,167,47,.55); }
        .so-light .so-card:hover { background: #fff; border-color: rgba(47,90,58,.45); box-shadow: 0 14px 30px rgba(22,50,31,.12); }
    }
</style>

{{-- Siluet pria & wanita berambut sanggul (placeholder foto) --}}
<svg class="so-sprite" aria-hidden="true" focusable="false">
    <symbol id="so-pria" viewBox="0 0 100 100">
        <path d="M14 100c0-22 15-34 36-34s36 12 36 34z" style="fill:#8fb09a"/>
        <circle cx="50" cy="38" r="17" style="fill:#d6e2d9"/>
        <path d="M33 36c-1-13 7-21 17-21s18 8 17 21c-3-7-9-10-17-10s-14 3-17 10z" style="fill:#14291a"/>
    </symbol>
    <symbol id="so-wanita" viewBox="0 0 100 100">
        <path d="M14 100c0-22 15-34 36-34s36 12 36 34z" style="fill:#8fb09a"/>
        <circle cx="50" cy="40" r="16" style="fill:#d6e2d9"/>
        <circle cx="50" cy="15" r="9" style="fill:#14291a"/>
        <path d="M33 40c-2-14 6-22 17-22s19 8 17 22c-2-8-8-13-17-13s-15 5-17 13z" style="fill:#14291a"/>
    </symbol>
</svg>

<div class="ab">

    @include('about._hero', [
        'title' => 'Profil Manajemen',
        'lead'  => 'Mengenal Jajaran Manajemen yang Menjadi Penggerak Perusahaan',
        'crumb' => 'Profil Manajemen',
    ])

    @foreach($bagian as $b)
        @if($b['levels']->isEmpty() && !$loop->first) @continue @endif

        <section class="{{ $b['class'] }}" @if($loop->first) id="struktur-organisasi" @endif>
            @if($loop->first)
                <p class="ab-label">Tim kami</p>
                <h2 class="ab-h2">Struktur Organisasi</h2>
                <p class="ab-p">
                    Dipimpin oleh tim profesional yang berpengalaman di industri kelapa sawit,
                    berkomitmen menjalankan perusahaan secara transparan dan bertanggung jawab.
                </p>
            @endif

            <div class="so-groups {{ $b['grid'] }}">
                @foreach($b['levels'] as $level => $anggota)
                    @php $kelas = $level <= 2 ? 'lead' : 'lv' . min($level, 5); @endphp
                    <div class="so-group">
                        <h3 class="so-head">
                            {{ $orgLevelLabels[$level] ?? 'Level ' . $level }}
                            @if($level >= 3)<span class="so-count">· {{ $anggota->count() }} orang</span>@endif
                        </h3>

                        <ul class="so-cards so-cards--{{ $kelas }}">
                            @foreach($anggota as $o)
                            <li class="so-card so-card--{{ $kelas }}">
                                <span class="so-av" aria-hidden="true">
                                    <svg viewBox="0 0 100 100" focusable="false"><use href="#{{ ($o['jk'] ?? 'l') === 'p' ? 'so-wanita' : 'so-pria' }}" width="100" height="100"/></svg>
                                    @if(!empty($o['foto']))
                                        <img src="{{ asset('images/struktur/' . $o['foto']) }}" alt="" loading="lazy" onerror="this.remove()">
                                    @endif
                                </span>
                                <div class="so-info">
                                    <p class="so-name">{{ $o['nama'] }}</p>
                                    <p class="so-role">{{ trim($o['jabatan']) }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

</div>
@endsection