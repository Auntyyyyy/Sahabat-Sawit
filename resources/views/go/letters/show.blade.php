@extends('layouts.go')

@section('page-title', 'Detail Surat')
@section('page-subtitle', $letter->nomor_surat . ' — ' . $letter->jenis)

@section('content')

@php
    $ext = $letter->lampiranEkstensi();
@endphp

<div class="panel">
    <div class="panel-head">
        <div class="head-title">
            <h2>{{ $letter->perihal }}</h2>
            <span class="status-pill {{ $letter->arahBadgeClass() }}">Surat {{ $letter->arah }}</span>
        </div>
        <a href="{{ route('go.letters.index') }}" class="panel-link">← Kembali</a>
    </div>

    <div class="detail-layout">
        {{-- Kolom kiri: lampiran --}}
        <div>
            @if($letter->lampiran)
                <div class="file-card">
                    <div class="file-icon {{ $ext === 'pdf' ? 'pdf' : 'word' }}">{{ strtoupper($ext) }}</div>
                    <div class="file-name">{{ $letter->lampiran_nama }}</div>
                    <div class="file-hint">Lampiran surat</div>
                    <div class="file-actions">
                        @if($letter->lampiranIsPdf())
                            <a href="{{ route('go.letters.lampiran', $letter) }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">Buka</a>
                        @endif
                        <a href="{{ route('go.letters.lampiran', [$letter, 'unduh' => 1]) }}" class="btn btn-secondary btn-sm">Unduh</a>
                    </div>
                </div>
            @else
                <div class="file-card empty">Belum ada lampiran.</div>
            @endif
        </div>

        {{-- Kolom kanan: ringkasan + rincian --}}
        <div>
            <div class="detail-stats">
                <div class="detail-stat">
                    <div class="label">Sifat</div>
                    <div class="value"><span class="status-pill {{ $letter->sifatBadgeClass() }}">{{ $letter->sifat }}</span></div>
                </div>

                <div class="detail-stat">
                    <div class="label">Status</div>
                    <div class="value"><span class="status-pill {{ $letter->statusBadgeClass() }}">{{ $letter->status }}</span></div>
                </div>

                <div class="detail-stat">
                    <div class="label">Tanggal Surat</div>
                    <div class="value">{{ $letter->tanggal_surat->locale('id')->translatedFormat('d M Y') }}</div>
                </div>
            </div>

            <dl class="detail-grid">
                <dt>Nomor Surat</dt>
                <dd>{{ $letter->nomor_surat }}</dd>

                <dt>Jenis Surat</dt>
                <dd>{{ $letter->jenis }}</dd>

                <dt>{{ $letter->pihakLabel() }}</dt>
                <dd>{{ $letter->pengirim_tujuan }}</dd>

                @if($letter->arah === 'Masuk')
                    <dt>Tanggal Diterima</dt>
                    <dd>{{ $letter->tanggal_diterima ? $letter->tanggal_diterima->locale('id')->translatedFormat('d F Y') : '—' }}</dd>
                @endif

                <dt>Perihal</dt>
                <dd>{{ $letter->perihal }}</dd>

                <dt>Keterangan</dt>
                <dd>{!! $letter->keterangan ? nl2br(e($letter->keterangan)) : '—' !!}</dd>
            </dl>

            <div class="detail-actions">
                <a href="{{ route('go.letters.edit', $letter) }}" class="btn btn-primary">Edit Surat</a>
                <a href="{{ route('go.letters.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>

@endsection