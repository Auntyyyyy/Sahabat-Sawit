@extends('layouts.go')

@section('page-title', 'Permintaan ' . $atkRequest->nomor)
@section('page-subtitle', $atkRequest->pemohon_nama . ' · ' . $atkRequest->tanggal->translatedFormat('d F Y'))

@section('extra-style')
.alert-error {
    background: #FBE7E8; border: 1px solid #F1C4C6; color: var(--red);
    border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem;
}
.alert-warning {
    background: #FDF1DF; border: 1px solid #F1D9AE; color: #8A5A12;
    border-radius: 10px; padding: .8rem 1.1rem; font-size: .84rem; margin-bottom: 1.1rem;
}
.alert-warning a { color: inherit; font-weight: 600; text-decoration: underline; }

.btn-danger-soft { background: #FBE7E8; color: var(--red); border: 1px solid #F1C4C6; }
.btn-danger-soft:hover { background: var(--red); color: #fff; border-color: var(--red); }

.steps { display: flex; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); }
.step { display: flex; align-items: center; gap: .55rem; flex: 1; color: var(--muted); font-size: .84rem; font-weight: 500; }
.step:last-child { flex: 0 0 auto; }
.step:not(:last-child)::after { content: ''; flex: 1; height: 2px; margin: 0 .9rem; border-radius: 2px; background: var(--line); }
.step-dot {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    background: #FBEAF1; color: var(--muted); font-size: .75rem; font-weight: 700;
}
.step.current { color: var(--rose-dark); font-weight: 600; }
.step.current .step-dot { background: var(--pink); color: #fff; }
.step.done { color: var(--green); }
.step.done .step-dot { background: var(--green); color: #fff; }
.step.done:not(:last-child)::after { background: var(--green); }

.step-note { padding: 1rem 1.5rem; border-bottom: 1px solid var(--line); background: #FFF8F8; color: var(--red); font-size: .85rem; font-weight: 500; }

.stage-hint { margin: 0 0 1.1rem; color: var(--muted); font-size: .86rem; }

.danger-zone {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
    margin-top: 1.25rem; padding-top: 1.1rem; border-top: 1px solid var(--line);
}
.danger-zone .danger-text { font-size: .84rem; color: var(--muted); }
.danger-zone .danger-text strong { display: block; color: var(--ink); font-size: .86rem; margin-bottom: .15rem; }

@media (max-width: 640px) {
    .step-label { display: none; }
    .step.current .step-label { display: inline; }
}
@endsection

@section('content')

@php
    $status = $atkRequest->status;
    $nextStatuses = $atkRequest->allowedTransitions();

    $actionLabels = [
        'Disetujui'  => 'Setujui',
        'Ditolak'    => 'Tolak',
        'Diserahkan' => 'Tandai Diserahkan',
        'Dibatalkan' => 'Batalkan & Kembalikan Stok',
    ];
    $needConfirm = [
        'Ditolak'    => 'Tolak permintaan ini?',
        'Dibatalkan' => 'Batalkan penyerahan? Stok barang akan dikembalikan.',
    ];
    $stageHint = [
        'Diajukan'   => 'Menunggu persetujuan. Setujui atau tolak permintaan ini.',
        'Disetujui'  => 'Siap diserahkan. Stok barang berkurang otomatis saat ditandai Diserahkan.',
        'Diserahkan' => 'Barang sudah diserahkan. Membatalkan akan mengembalikan stok.',
        'Ditolak'    => 'Permintaan ini ditolak. Tidak ada perubahan lanjutan.',
        'Dibatalkan' => 'Penyerahan dibatalkan dan stok sudah dikembalikan. Tidak ada perubahan lanjutan.',
    ];

    $flow = ['Diajukan', 'Disetujui', 'Diserahkan'];
    $idx = array_search($status, $flow, true);

    $items = $atkRequest->items;
    $cekStok = in_array($status, ['Diajukan', 'Disetujui'], true);
    $adaKurang = $cekStok && $items->contains(fn ($r) => $r->item && $r->item->stok < $r->jumlah);
    $totalUnit = $items->sum('jumlah');
@endphp

@if (session('error'))
<div class="alert-error">{{ session('error') }}</div>
@endif

<div class="panel">
    <div class="panel-head">
        <div class="head-title">
            <h2>{{ $atkRequest->nomor }}</h2>
            <span class="status-pill {{ $atkRequest->statusPillClass() }}">{{ $status }}</span>
        </div>
        <a href="{{ route('go.atk-requests.index') }}" class="panel-link">← Kembali</a>
    </div>

    @if($idx !== false)
        <div class="steps">
            @foreach($flow as $i => $label)
                <div class="step {{ ($i < $idx || $status === 'Diserahkan') ? 'done' : ($i === $idx ? 'current' : '') }}">
                    <span class="step-dot">{{ ($i < $idx || $status === 'Diserahkan') ? '✓' : $i + 1 }}</span>
                    <span class="step-label">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="step-note">{{ $stageHint[$status] ?? $status }}</div>
    @endif

    <div style="padding:1.5rem 1.5rem 0;">
        <div class="detail-stats">
            <div class="detail-stat">
                <div class="label">Pemohon</div>
                <div class="value">{{ $atkRequest->pemohon_nama }}</div>
                <div class="hint">{{ $atkRequest->pemohon_divisi ?: 'Divisi tidak dicatat' }}</div>
            </div>

            <div class="detail-stat">
                <div class="label">Tanggal Permintaan</div>
                <div class="value">{{ $atkRequest->tanggal->translatedFormat('d M Y') }}</div>
                <div class="hint">{{ $atkRequest->tanggal->translatedFormat('l') }}</div>
            </div>

            <div class="detail-stat">
                <div class="label">Barang Diminta</div>
                <div class="value">{{ $items->count() }} jenis</div>
                <div class="hint">Total {{ $totalUnit }} unit</div>
            </div>
        </div>
    </div>

    <dl class="detail-grid" style="padding-top:.25rem;">
        <dt>Nomor</dt>
        <dd>{{ $atkRequest->nomor }}</dd>

        <dt>Catatan</dt>
        <dd>{!! $atkRequest->catatan ? nl2br(e($atkRequest->catatan)) : '—' !!}</dd>
    </dl>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Barang yang Diminta</h2>
        <span style="font-size:.82rem; color: var(--muted);">{{ $items->count() }} jenis · {{ $totalUnit }} unit</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Jumlah Diminta</th>
                    <th>Stok Saat Ini</th>
                    <th>Ketersediaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $row)
                @php
                    $stok = $row->item?->stok;
                    $kurang = $cekStok && $row->item && $stok < $row->jumlah;
                @endphp
                <tr>
                    <td style="white-space:nowrap;">{{ $row->item?->kode ?? '—' }}</td>
                    <td>{{ $row->item?->nama ?? 'Barang sudah dihapus' }}</td>
                    <td><strong>{{ $row->jumlah }}</strong> {{ $row->item?->satuan }}</td>
                    <td>{{ $stok }} {{ $row->item?->satuan }}</td>
                    <td>
                        @if($cekStok && $row->item)
                            <span class="status-pill {{ $kurang ? 'nonaktif' : 'aktif' }}">
                                {{ $kurang ? 'Kurang ' . ($row->jumlah - $stok) : 'Cukup' }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Ubah Status</h2>
    </div>
    <div class="panel-body">
        <p class="stage-hint">{{ $stageHint[$status] ?? '' }}</p>

        @if($adaKurang)
            <div class="alert-warning">
                Stok sebagian barang belum cukup untuk permintaan ini.
                <a href="{{ route('go.atk-stock-ins.create') }}">Catat barang masuk</a> dulu sebelum menandai Diserahkan.
            </div>
        @endif

        @if(!empty($nextStatuses))
            <div style="display:flex; gap:.6rem; flex-wrap:wrap;">
                @foreach($nextStatuses as $next)
                <form action="{{ route('go.atk-requests.status', $atkRequest) }}" method="POST"
                      @if(isset($needConfirm[$next])) onsubmit="return confirm('{{ $needConfirm[$next] }}');" @endif>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $next }}">
                    <button type="submit" class="btn {{ in_array($next, ['Disetujui', 'Diserahkan']) ? 'btn-primary' : 'btn-danger-soft' }}">
                        {{ $actionLabels[$next] ?? $next }}
                    </button>
                </form>
                @endforeach
            </div>
        @endif

        <div class="danger-zone">
            @if($status !== 'Diserahkan')
                <div class="danger-text">
                    <strong>Hapus permintaan</strong>
                    Permintaan beserta daftar barangnya dihapus permanen.
                </div>
                <form action="{{ route('go.atk-requests.destroy', $atkRequest) }}" method="POST"
                      onsubmit="return confirm('Hapus permintaan ini? Tindakan ini tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-soft">Hapus Permintaan</button>
                </form>
            @else
                <div class="danger-text">
                    <strong>Hapus permintaan</strong>
                    Permintaan yang sudah diserahkan tidak bisa dihapus. Batalkan dulu supaya stok dikembalikan.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection