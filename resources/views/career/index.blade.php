@extends('layouts.app')

@section('title', 'Karir — PT Sahabat Sawit')

@section('content')
@include('career._style')

{{-- Foto latar hero: taruh file Anda di public/images/ lalu samakan namanya di sini. --}}
@php
    $fotoKarir = 'karir.jpeg';
@endphp

<style>
    /* Hero berfoto untuk halaman daftar lowongan */
    .kr-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(70vh, 44rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .kr-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.6) 50%, rgba(22,50,31,.2) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .kr-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .kr-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) clamp(1.25rem, 5vw, 3.5rem) clamp(3rem, 8vh, 4.5rem); }
    .kr-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .kr-crumb a:hover { color: var(--krem); }
    .kr-crumb [aria-current] { color: var(--krem); }
    .kr-hero .kr-title { margin-top: 1.5rem; max-width: 12ch; font-size: clamp(2.8rem, 9vw, 7rem); line-height: 1; }
    .kr-sec-list { padding-top: clamp(3rem, 8vh, 5rem); }
    .kr-sec-list .kr-list { margin-top: 1.25rem; }
</style>

<div class="kr">

    {{-- HERO berfoto --}}
    <header class="kr-hero">
        <img class="kr-bg" src="{{ asset('images/' . $fotoKarir) }}" alt="" onerror="this.style.display='none'">
        <div class="kr-hero-inner">
            <div class="kr-inner">
                <nav class="kr-crumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">Karir</span>
                </nav>
                <h1 class="kr-title">Karir</h1>
                <p class="kr-lead">
                    Bergabunglah dengan PT Sahabat Sawit Rokan Sejahtera dan tumbuh bersama kami di industri perkebunan dan kelapa sawit Rokan Hilir, Riau.
                </p>
            </div>
        </div>
    </header>

    {{-- DAFTAR LOWONGAN --}}
    <section class="kr-sec kr-sec-list">
        <div class="kr-inner">
            @if ($lowongan->isNotEmpty())
            <p class="kr-label">{{ $lowongan->count() }} lowongan dibuka</p>
            <div class="kr-list">
                @foreach ($lowongan as $l)
                <a class="kr-row" href="{{ route('career.show', $l->slug) }}">
                    <h2>{{ $l->judul }}</h2>
                    <p class="kr-meta">
                        <span>{{ $l->lokasi }}</span>
                        @if ($l->batas_waktu)
                            <span>Batas lamaran {{ $l->batas_waktu->locale('id')->translatedFormat('j F Y') }}</span>
                        @endif
                    </p>
                </a>
                @endforeach
            </div>
            @else
            <p class="kr-empty" style="margin-top:0">Belum ada lowongan yang dibuka saat ini. Silakan cek kembali nanti.</p>
            @endif
        </div>
    </section>

</div>
@endsection