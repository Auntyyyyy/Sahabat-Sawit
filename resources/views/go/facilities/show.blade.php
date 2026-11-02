@extends('layouts.go')

@section('page-title', 'Detail Fasilitas')
@section('page-subtitle', $facility->kode_fasilitas . ' — ' . $facility->nama)

@section('content')

@php
    $perawatan = $facility->tanggal_perawatan_terakhir;
@endphp

@if($aktif)
    <div class="alert-warning" style="margin-bottom:1.5rem;">
        Fasilitas ini sedang dipinjam oleh <strong>{{ $aktif->peminjam_nama }}</strong> ({{ $aktif->peminjam_divisi }})
        sampai {{ $aktif->tanggal_selesai->translatedFormat('d F Y') }}.
        <a href="{{ route('go.facility-loans.show', $aktif) }}">Lihat peminjaman</a>
    </div>
@endif

<div class="panel">
    <div class="panel-head">
        <div class="head-title">
            <h2>{{ $facility->nama }}</h2>
            <span class="status-pill {{ $facility->kondisiBadgeClass() }}">{{ $facility->kondisi }}</span>
        </div>
        <a href="{{ route('go.facilities.index') }}" class="panel-link">← Kembali</a>
    </div>

    <div class="detail-layout">
        {{-- Kolom kiri: foto --}}
        <div>
            @if($facility->foto)
                <a href="{{ asset('storage/' . $facility->foto) }}" target="_blank" rel="noopener" class="detail-photo" title="Klik untuk melihat ukuran penuh">
                    <img src="{{ asset('storage/' . $facility->foto) }}" alt="{{ $facility->nama }}">
                </a>
            @else
                <div class="detail-photo empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/></svg>
                    Belum ada foto
                </div>
            @endif
        </div>

        {{-- Kolom kanan: ringkasan + rincian --}}
        <div>
            <div class="detail-stats">
                <div class="detail-stat">
                    <div class="label">Kapasitas</div>
                    <div class="value">{{ $facility->kapasitas ?? '—' }}</div>
                </div>

                <div class="detail-stat">
                    <div class="label">Perawatan Terakhir</div>
                    <div class="value">{{ $perawatan ? $perawatan->locale('id')->translatedFormat('d M Y') : '—' }}</div>
                    <div class="hint">{{ $perawatan ? $perawatan->locale('id')->diffForHumans() : 'Belum pernah dicatat' }}</div>
                </div>

                <div class="detail-stat">
                    <div class="label">Kondisi</div>
                    <div class="value">{{ $facility->kondisi }}</div>
                </div>
            </div>

            <dl class="detail-grid">
                <dt>Kode Fasilitas</dt>
                <dd>{{ $facility->kode_fasilitas }}</dd>

                <dt>Kategori</dt>
                <dd>{{ $facility->kategori }}</dd>

                <dt>Lokasi</dt>
                <dd>{{ $facility->lokasi ?: '—' }}</dd>

                <dt>Penanggung Jawab</dt>
                <dd>{{ $facility->penanggung_jawab ?: '—' }}</dd>

                <dt>Keterangan</dt>
                <dd>{!! $facility->keterangan ? nl2br(e($facility->keterangan)) : '—' !!}</dd>
            </dl>

            <div class="detail-actions">
                <a href="{{ route('go.facilities.edit', $facility) }}" class="btn btn-primary">Edit Fasilitas</a>
                <a href="{{ route('go.facility-loans.create', ['facility_id' => $facility->id]) }}" class="btn btn-secondary">Buat Peminjaman</a>
                <a href="{{ route('go.facilities.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Riwayat Peminjaman</h2>
        <a href="{{ route('go.facility-loans.index', ['facility_id' => $facility->id]) }}" class="panel-link">Lihat semua →</a>
    </div>

    @if($riwayat->isEmpty())
        <div class="empty-note">Belum ada peminjaman untuk fasilitas ini.</div>
    @else
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Peminjam</th>
                        <th>Periode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $loan)
                    <tr>
                        <td style="white-space:nowrap;"><a href="{{ route('go.facility-loans.show', $loan) }}" class="action-view">{{ $loan->nomor }}</a></td>
                        <td>
                            {{ $loan->peminjam_nama }}
                            <div style="font-size:.78rem; color: var(--muted);">{{ $loan->peminjam_divisi }}</div>
                        </td>
                        <td style="white-space:nowrap;">{{ $loan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $loan->tanggal_selesai->translatedFormat('d M Y') }}</td>
                        <td><span class="status-pill {{ $loan->statusPillClass() }}">{{ $loan->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection