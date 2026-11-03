@extends('layouts.go')

@section('page-title', 'Detail Surat')
@section('page-subtitle', $letter->nomor_surat . ' — ' . $letter->jenis)

@section('content')

@php
    $ext = $letter->lampiranEkstensi();
    $status = $letter->status;
    $nextStatuses = $letter->allowedTransitions();

    $flow = ['Baru', 'Diproses', 'Selesai', 'Diarsipkan'];
    $idx = array_search($status, $flow, true);

    $stageHint = [
        'Baru'       => 'Surat baru dicatat. Tandai Diproses saat mulai ditindaklanjuti, atau langsung Selesai.',
        'Diproses'   => 'Surat sedang ditindaklanjuti. Tandai Selesai setelah tuntas.',
        'Selesai'    => 'Surat sudah selesai. Arsipkan kalau tidak perlu lagi di daftar aktif, atau buka kembali kalau perlu tindak lanjut.',
        'Diarsipkan' => 'Surat sudah diarsipkan. Buka kembali kalau perlu ditindaklanjuti lagi.',
    ];

    $actionLabel = function (string $target) use ($status) {
        return match (true) {
            $target === 'Diproses' && in_array($status, ['Selesai', 'Diarsipkan'], true) => 'Buka Kembali',
            $target === 'Diproses'  => 'Tandai Diproses',
            $target === 'Selesai'   => 'Tandai Selesai',
            $target === 'Diarsipkan' => 'Arsipkan',
            default => $target,
        };
    };
@endphp

<div class="panel">
    <div class="panel-head">
        <div class="head-title">
            <h2>{{ $letter->perihal }}</h2>
            <span class="status-pill {{ $letter->arahBadgeClass() }}">Surat {{ $letter->arah }}</span>
        </div>
        <a href="{{ route('go.letters.index') }}" class="panel-link">← Kembali</a>
    </div>

    @if($idx !== false)
        <div class="steps">
            @foreach($flow as $i => $label)
                <div class="step {{ ($i < $idx || $status === 'Diarsipkan') ? 'done' : ($i === $idx ? 'current' : '') }}">
                    <span class="step-dot">{{ ($i < $idx || $status === 'Diarsipkan') ? '✓' : $i + 1 }}</span>
                    <span class="step-label">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    @endif

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

<div class="panel">
    <div class="panel-head">
        <h2>Ubah Status</h2>
    </div>
    <div class="panel-body">
        <p class="stage-hint">{{ $stageHint[$status] ?? '' }}</p>

        <div style="display:flex; gap:.6rem; flex-wrap:wrap;">
            @foreach($nextStatuses as $next)
            <form action="{{ route('go.letters.status', $letter) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $next }}">
                <button type="submit" class="btn {{ ($next === 'Selesai' || ($next === 'Diproses' && $status === 'Baru')) ? 'btn-primary' : 'btn-secondary' }}">
                    {{ $actionLabel($next) }}
                </button>
            </form>
            @endforeach
        </div>
    </div>
</div>

@endsection