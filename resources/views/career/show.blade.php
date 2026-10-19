@extends('layouts.app')

@section('title', $lowongan->judul . ' — Karir PT Sahabat Sawit')

@section('content')
@include('career._style')

@php
    $url  = urlencode(url()->current());
    $teks = urlencode($lowongan->judul . ' - PT Sahabat Sawit Rokan Sejahtera');
@endphp

<div class="kr">
    <section class="kr-sec">
        <div class="kr-inner">
            <a class="kr-back kr-noprint" href="{{ route('career') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                Semua lowongan
            </a>

            <h1 class="kr-title">{{ $lowongan->judul }}</h1>

            <div class="kr-split">
                <p class="kr-label">Deskripsi</p>
                <div>
                    <ul class="kr-points">
                        @foreach ($lowongan->daftar_deskripsi as $poin)
                            <li>{{ $poin }}</li>
                        @endforeach
                    </ul>

                    <p class="kr-info">Lokasi penempatan: {{ $lowongan->lokasi }}</p>
                    @if ($lowongan->batas_waktu)
                        <p class="kr-info" style="margin-top:.25rem">Batas lamaran: {{ $lowongan->batas_waktu->locale('id')->translatedFormat('j F Y') }}</p>
                    @endif

                    <div class="kr-actions kr-noprint">
                        {{-- LANGKAH 2: tombol "Apply Sekarang" (menggulir ke form) ditambahkan di sini --}}
                        <button type="button" class="kr-link" onclick="window.print()">Cetak</button>
                    </div>

                    <div class="kr-share kr-noprint">
                        <p class="kr-label">Bagikan</p>
                        <div class="kr-share-row">
                            <a class="kr-ic" href="https://wa.me/?text={{ $teks }}%20{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan lewat WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            <a class="kr-ic" href="mailto:?subject={{ $teks }}&body={{ $url }}" aria-label="Bagikan lewat email"><i class="bi bi-envelope"></i></a>
                            <a class="kr-ic" href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                            <a class="kr-ic" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <button type="button" class="kr-ic" id="krCopy" aria-label="Salin tautan"><i class="bi bi-link-45deg"></i></button>
                            <span class="kr-copied" id="krCopied" role="status"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LANGKAH 2: Form Aplikasi ditambahkan di sini --}}
        </div>
    </section>
</div>

<script>
    (function () {
        const btn = document.getElementById('krCopy');
        const note = document.getElementById('krCopied');
        if (!btn) return;
        btn.addEventListener('click', function () {
            navigator.clipboard.writeText(window.location.href).then(function () {
                note.textContent = 'Tautan disalin';
                setTimeout(function () { note.textContent = ''; }, 2000);
            });
        });
    })();
</script>
@endsection