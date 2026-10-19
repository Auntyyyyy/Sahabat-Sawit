@extends('layouts.app')

@section('title', 'Karir — PT Sahabat Sawit')

@section('content')
@include('career._style')

<div class="kr">
    <section class="kr-sec">
        <div class="kr-inner">
            <h1 class="kr-title">Karir</h1>
            <p class="kr-lead">
                Bergabunglah dengan PT Sahabat Sawit Rokan Sejahtera dan tumbuh bersama kami di industri perkebunan dan kelapa sawit Rokan Hilir, Riau.
            </p>

            @if ($lowongan->isNotEmpty())
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
            <p class="kr-empty">Belum ada lowongan yang dibuka saat ini. Silakan cek kembali nanti.</p>
            @endif
        </div>
    </section>
</div>
@endsection