@extends('layouts.go')

@section('page-title', 'Peminjaman ' . $facilityLoan->nomor)
@section('page-subtitle', ($facilityLoan->facility?->nama ?? 'Fasilitas') . ' · ' . $facilityLoan->peminjam_nama)

@section('content')

@php
    $status = $facilityLoan->status;
    $facility = $facilityLoan->facility;
    $nextStatuses = array_values(array_diff($facilityLoan->allowedTransitions(), ['Selesai']));
    $bisaSelesai = $facilityLoan->canTransitionTo('Selesai');

    $actionLabels = [
        'Disetujui'        => 'Setujui',
        'Ditolak'          => 'Tolak',
        'Sedang Digunakan' => 'Check-in (Mulai Digunakan)',
        'Dibatalkan'       => 'Batalkan Peminjaman',
    ];
    $needConfirm = [
        'Ditolak'    => 'Tolak peminjaman ini?',
        'Dibatalkan' => 'Batalkan peminjaman ini?',
    ];
    $stageHint = [
        'Diajukan'         => 'Menunggu persetujuan. Setujui atau tolak peminjaman ini.',
        'Disetujui'        => 'Jadwal sudah disetujui. Tekan Check-in saat peminjam mulai menggunakan fasilitas.',
        'Sedang Digunakan' => 'Fasilitas sedang digunakan. Catat pengembalian di bawah setelah selesai.',
        'Selesai'          => 'Fasilitas sudah dikembalikan. Peminjaman ini sudah selesai.',
        'Ditolak'          => 'Peminjaman ini ditolak. Tidak ada perubahan lanjutan.',
        'Dibatalkan'       => 'Peminjaman ini dibatalkan. Tidak ada perubahan lanjutan.',
    ];

    $flow = ['Diajukan', 'Disetujui', 'Sedang Digunakan', 'Selesai'];
    $idx = array_search($status, $flow, true);

    $melebihi = $facilityLoan->jumlah_orang && $facility?->kapasitas && $facilityLoan->jumlah_orang > $facility->kapasitas;
    $tidakTersedia = in_array($status, ['Diajukan', 'Disetujui'], true)
        && $facility && in_array($facility->kondisi, \App\Models\Facility::KONDISI_TIDAK_TERSEDIA, true);
@endphp

<div class="panel">
    <div class="panel-head">
        <div class="head-title">
            <h2>{{ $facilityLoan->nomor }}</h2>
            <span class="status-pill {{ $facilityLoan->statusPillClass() }}">{{ $status }}</span>
        </div>
        <a href="{{ route('go.facility-loans.index') }}" class="panel-link">← Kembali</a>
    </div>

    @if($idx !== false)
        <div class="steps">
            @foreach($flow as $i => $label)
                <div class="step {{ ($i < $idx || $status === 'Selesai') ? 'done' : ($i === $idx ? 'current' : '') }}">
                    <span class="step-dot">{{ ($i < $idx || $status === 'Selesai') ? '✓' : $i + 1 }}</span>
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
                <div class="label">Fasilitas</div>
                <div class="value">
                    @if($facility)
                        <a href="{{ route('go.facilities.show', $facility) }}" style="color:inherit;">{{ $facility->nama }}</a>
                    @else
                        —
                    @endif
                </div>
                <div class="hint">{{ $facility?->kode_fasilitas }} · {{ $facility?->kategori }}</div>
            </div>

            <div class="detail-stat">
                <div class="label">Peminjam</div>
                <div class="value">{{ $facilityLoan->peminjam_nama }}</div>
                <div class="hint">{{ $facilityLoan->peminjam_divisi }}</div>
            </div>

            <div class="detail-stat">
                <div class="label">Periode</div>
                <div class="value">{{ $facilityLoan->tanggal_mulai->translatedFormat('d M') }} – {{ $facilityLoan->tanggal_selesai->translatedFormat('d M Y') }}</div>
                <div class="hint">{{ $facilityLoan->durasiHari() }} hari</div>
            </div>
        </div>
    </div>

    <dl class="detail-grid" style="padding-top:.25rem;">
        <dt>Keperluan</dt>
        <dd>{{ $facilityLoan->keperluan }}</dd>

        <dt>Jumlah Orang</dt>
        <dd>
            {{ $facilityLoan->jumlah_orang ?? '—' }}
            @if($facility?->kapasitas)
                <span style="color: var(--muted); font-weight:400;">(kapasitas {{ $facility->kapasitas }})</span>
            @endif
        </dd>

        <dt>Catatan</dt>
        <dd>{!! $facilityLoan->catatan ? nl2br(e($facilityLoan->catatan)) : '—' !!}</dd>

        @if($status === 'Selesai')
            <dt>Dikembalikan</dt>
            <dd>{{ $facilityLoan->tanggal_kembali?->translatedFormat('d F Y') ?? '—' }}</dd>

            <dt>Kondisi Kembali</dt>
            <dd><span class="status-pill {{ $facilityLoan->kondisiKembaliPillClass() }}">{{ $facilityLoan->kondisi_kembali }}</span></dd>

            <dt>Catatan Kembali</dt>
            <dd>{!! $facilityLoan->catatan_kembali ? nl2br(e($facilityLoan->catatan_kembali)) : '—' !!}</dd>
        @endif
    </dl>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Ubah Status</h2>
    </div>
    <div class="panel-body">
        <p class="stage-hint">{{ $stageHint[$status] ?? '' }}</p>

        @if($konflik->isNotEmpty())
            <div class="alert-warning">
                Jadwal bentrok dengan:
                @foreach($konflik as $k)
                    <a href="{{ route('go.facility-loans.show', $k) }}">{{ $k->nomor }}</a>
                    ({{ $k->peminjam_nama }}, {{ $k->tanggal_mulai->translatedFormat('d M') }} – {{ $k->tanggal_selesai->translatedFormat('d M Y') }}){{ $loop->last ? '.' : ',' }}
                @endforeach
                Peminjaman ini tidak bisa disetujui selama jadwalnya bentrok.
            </div>
        @endif

        @if($melebihi)
            <div class="alert-warning">
                Jumlah orang ({{ $facilityLoan->jumlah_orang }}) melebihi kapasitas fasilitas ({{ $facility->kapasitas }}).
            </div>
        @endif

        @if($tidakTersedia)
            <div class="alert-warning">
                Kondisi fasilitas saat ini <strong>{{ $facility->kondisi }}</strong>. Pastikan fasilitas layak dipakai sebelum melanjutkan.
            </div>
        @endif

        @if(!empty($nextStatuses))
            <div style="display:flex; gap:.6rem; flex-wrap:wrap;">
                @foreach($nextStatuses as $next)
                <form action="{{ route('go.facility-loans.status', $facilityLoan) }}" method="POST"
                      @if(isset($needConfirm[$next])) onsubmit="return confirm('{{ $needConfirm[$next] }}');" @endif>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $next }}">
                    <button type="submit" class="btn {{ in_array($next, ['Disetujui', 'Sedang Digunakan']) ? 'btn-primary' : 'btn-danger-soft' }}">
                        {{ $actionLabels[$next] ?? $next }}
                    </button>
                </form>
                @endforeach
            </div>
        @endif

        @if($bisaSelesai)
            <form action="{{ route('go.facility-loans.return', $facilityLoan) }}" method="PATCH" style="display:none;"></form>
            <form action="{{ route('go.facility-loans.return', $facilityLoan) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-grid" style="padding:0 0 1.1rem;">
                    <div class="form-field">
                        <label>Tanggal Dikembalikan</label>
                        <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', now()->format('Y-m-d')) }}">
                        @error('tanggal_kembali') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label>Kondisi Saat Dikembalikan</label>
                        <select name="kondisi_kembali">
                            @foreach($kondisiOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('kondisi_kembali', 'Baik') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('kondisi_kembali') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-field full">
                        <label>Catatan Pengembalian</label>
                        <textarea name="catatan_kembali" rows="2" placeholder="mis. kunci sudah dikembalikan, ada kerusakan kecil pada AC (opsional)">{{ old('catatan_kembali') }}</textarea>
                        @error('catatan_kembali') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Selesaikan & Catat Pengembalian</button>
                <p style="margin:.8rem 0 0; color: var(--muted); font-size:.8rem;">
                    Kalau kondisi saat dikembalikan bukan Baik, kondisi fasilitas ikut diperbarui otomatis.
                </p>
            </form>
        @endif

        <div class="danger-zone">
            @if($status !== 'Sedang Digunakan')
                <div class="danger-text">
                    <strong>Hapus peminjaman</strong>
                    Data peminjaman ini dihapus permanen.
                </div>
                <form action="{{ route('go.facility-loans.destroy', $facilityLoan) }}" method="POST"
                      onsubmit="return confirm('Hapus peminjaman ini? Tindakan ini tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-soft">Hapus Peminjaman</button>
                </form>
            @else
                <div class="danger-text">
                    <strong>Hapus peminjaman</strong>
                    Peminjaman yang sedang berjalan tidak bisa dihapus. Selesaikan pengembaliannya dulu.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection