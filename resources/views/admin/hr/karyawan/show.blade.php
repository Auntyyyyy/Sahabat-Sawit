@extends('admin.layouts.hr')

@section('page-title', 'Detail Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    @php
        // Inisial dua kata pertama nama, dipakai kalau karyawan belum punya foto
        $inisial = collect(preg_split('/\s+/', trim($karyawan->name)))
            ->filter()
            ->take(2)
            ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
            ->implode('');

        $foto = $karyawan->photo ? asset('storage/' . $karyawan->photo) : null;
        $tglMasuk = $karyawan->join_date->locale('id')->translatedFormat('d F Y');
        $tglLahir = $karyawan->birth_date ? $karyawan->birth_date->locale('id')->translatedFormat('d F Y') : null;
        $usia = $karyawan->birth_date ? $karyawan->birth_date->age : null;
        $telepon = $karyawan->phone ? preg_replace('/[^0-9+]/', '', $karyawan->phone) : null;
    @endphp

    <a href="{{ route('hr.karyawan.index') }}" class="panel-link profile-back">&larr; Kembali ke Daftar</a>

    {{-- ================= IDENTITAS ================= --}}
    <section class="profile">
        <div class="profile-head">
            @if ($foto)
                <img src="{{ $foto }}" alt="Foto {{ $karyawan->name }}" class="profile-avatar profile-avatar-img">
            @else
                <div class="profile-avatar" aria-hidden="true">{{ $inisial }}</div>
            @endif

            <div class="profile-id">
                <h2 class="profile-name">{{ $karyawan->name }}</h2>
                <p class="profile-role">{{ $karyawan->position }}</p>
                <p class="profile-division">{{ $karyawan->division }}</p>
            </div>

            <span class="status-pill {{ $karyawan->status }}">{{ ucfirst($karyawan->status) }}</span>
        </div>

        <dl class="profile-facts">
            <div>
                <dt>Bergabung</dt>
                <dd>{{ $tglMasuk }}</dd>
            </div>
            <div>
                <dt>Masa kerja</dt>
                <dd>{{ $karyawan->masa_kerja }}</dd>
            </div>
            <div>
                <dt>Usia</dt>
                <dd>{{ $usia !== null ? $usia . ' tahun' : '-' }}</dd>
            </div>
        </dl>
    </section>

    {{-- ================= KONTAK + DATA PRIBADI ================= --}}
    <div class="profile-cols">
        <section class="panel">
            <div class="panel-head"><h2>Kontak</h2></div>
            <ul class="info-list">
                <li>
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <div>
                        <div class="info-label">Email</div>
                        <a href="mailto:{{ $karyawan->email }}" class="info-value">{{ $karyawan->email }}</a>
                    </div>
                </li>
                <li>
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </span>
                    <div>
                        <div class="info-label">No. telepon</div>
                        @if ($telepon)
                            <a href="tel:{{ $telepon }}" class="info-value">{{ $karyawan->phone }}</a>
                        @else
                            <span class="info-value">-</span>
                        @endif
                    </div>
                </li>
                <li>
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <div>
                        <div class="info-label">Alamat</div>
                        <span class="info-value">{{ $karyawan->address ?: '-' }}</span>
                    </div>
                </li>
            </ul>
        </section>

        <section class="panel">
            <div class="panel-head"><h2>Data pribadi</h2></div>
            <ul class="info-list">
                <li>
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <div>
                        <div class="info-label">Jenis kelamin</div>
                        <span class="info-value">{{ $karyawan->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                </li>
                <li>
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <div>
                        <div class="info-label">Tanggal lahir</div>
                        <span class="info-value">{{ $tglLahir ?? '-' }}</span>
                    </div>
                </li>
            </ul>
        </section>
    </div>

    <p class="profile-note">Data karyawan dikelola oleh Personalia.</p>

@endsection

@section('extra-style')
    .profile-back { display: inline-block; margin-bottom: 1rem; }

    /* ===== Identitas ===== */
    .profile { background: #fff; border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; }
    .profile-head { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; padding: 1.75rem 2rem; background: var(--navy); color: #fff; }
    .profile-avatar { width: 88px; height: 88px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: var(--blue-light, #4FA3D1); color: #fff; font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 600; border: 3px solid rgba(255,255,255,.35); }
    .profile-avatar-img { object-fit: cover; }
    .profile-id { flex: 1; min-width: 200px; }
    .profile-name { margin: 0; font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 700; line-height: 1.25; color: #fff; }
    .profile-role { margin: .4rem 0 0; font-size: 1rem; font-weight: 500; color: #fff; }
    .profile-division { margin: .15rem 0 0; font-size: .875rem; color: #B9C9DC; }
    .profile-head .status-pill { margin-left: auto; align-self: flex-start; }

    .profile-facts { display: grid; grid-template-columns: repeat(3, 1fr); margin: 0; }
    .profile-facts > div { padding: 1.1rem 2rem; }
    .profile-facts > div + div { border-left: 1px solid var(--line); }
    .profile-facts dt { margin: 0 0 .2rem; font-size: .8rem; color: var(--muted); }
    .profile-facts dd { margin: 0; font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 600; color: var(--navy); }

    /* ===== Kontak + data pribadi ===== */
    .profile-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start; }
    .info-list { list-style: none; margin: 0; padding: .5rem 1.5rem 1rem; }
    .info-list li { display: flex; align-items: flex-start; gap: .9rem; padding: .85rem 0; }
    .info-list li + li { border-top: 1px solid var(--line); }
    .info-icon { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #EEF3F8; color: var(--blue); }
    .info-label { font-size: .8rem; color: var(--muted); }
    .info-value { font-size: .95rem; font-weight: 500; color: var(--ink); word-break: break-word; }
    a.info-value { color: var(--blue); text-decoration: none; }
    a.info-value:hover, a.info-value:focus-visible { text-decoration: underline; }

    .profile-note { margin: 1.25rem 0 0; font-size: .8rem; color: var(--muted); }

    @media (max-width: 900px) {
        .profile-cols { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
        .profile-head { padding: 1.25rem; }
        .profile-head .status-pill { margin-left: 0; }
        .profile-facts { grid-template-columns: 1fr; }
        .profile-facts > div { padding: .9rem 1.25rem; }
        .profile-facts > div + div { border-left: 0; border-top: 1px solid var(--line); }
    }
@endsection