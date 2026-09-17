@extends('admin.layouts.hr')

@section('page-title', 'Data Karyawan')
@section('page-subtitle', 'Statistik dan daftar seluruh karyawan')

@section('content')

    <div class="summary-row">
        <div class="summary-item">
            <div class="label">Total Karyawan</div>
            <div class="value">{{ $total }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Karyawan Aktif</div>
            <div class="value">{{ $perStatus['aktif'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Cuti / Nonaktif</div>
            <div class="value attention">{{ $perStatus['cuti'] + $perStatus['nonaktif'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Rata-rata Masa Kerja</div>
            <div class="value">{{ $avgMasaKerjaLabel }}</div>
        </div>
    </div>

    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Distribusi per Divisi</h2></div>
            <div class="panel-body">
                @forelse ($perDivisi as $divisi => $jumlah)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $divisi }}</span>
                            <span>{{ $jumlah }} orang</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:.85rem;">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Status Karyawan</h2></div>
            <div class="panel-body">
                <div class="bar-row">
                    <div class="bar-label"><span>Aktif</span><span>{{ $perStatus['aktif'] }} orang</span></div>
                    <div class="bar-track"><div class="bar-fill green" style="width: {{ $total > 0 ? round($perStatus['aktif'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Cuti</span><span>{{ $perStatus['cuti'] }} orang</span></div>
                    <div class="bar-track"><div class="bar-fill amber" style="width: {{ $total > 0 ? round($perStatus['cuti'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Nonaktif</span><span>{{ $perStatus['nonaktif'] }} orang</span></div>
                    <div class="bar-track"><div class="bar-fill red" style="width: {{ $total > 0 ? round($perStatus['nonaktif'] / $total * 100) : 0 }}%;"></div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Demografi &mdash; Jenis Kelamin</h2></div>
            <div class="panel-body">
                <div class="bar-row">
                    <div class="bar-label"><span>Laki-laki</span><span>{{ $perGender['L'] }} orang</span></div>
                    <div class="bar-track"><div class="bar-fill" style="width: {{ $total > 0 ? round($perGender['L'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Perempuan</span><span>{{ $perGender['P'] }} orang</span></div>
                    <div class="bar-track"><div class="bar-fill light" style="width: {{ $total > 0 ? round($perGender['P'] / $total * 100) : 0 }}%;"></div></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Demografi &mdash; Kelompok Usia</h2></div>
            <div class="panel-body">
                @foreach ($ageGroups as $label => $jumlah)
                    <div class="bar-row">
                        <div class="bar-label"><span>{{ $label }} tahun</span><span>{{ $jumlah }} orang</span></div>
                        <div class="bar-track"><div class="bar-fill light" style="width: {{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%;"></div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Karyawan</h2>
            <a href="{{ route('hr.karyawan.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
        </div>

        <form method="GET" action="{{ route('hr.karyawan.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Cari nama, email, divisi..." value="{{ request('search') }}">
            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="cuti" {{ request('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>

        @if ($karyawans->isEmpty())
            <div class="empty-note">Belum ada data karyawan yang cocok. Klik "Tambah Karyawan" untuk menambahkan.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Masa Kerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawans as $k)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $k->name }}</div>
                                <div style="color:var(--muted); font-size:.8rem;">{{ $k->email }}</div>
                            </td>
                            <td>{{ $k->division }}</td>
                            <td>{{ $k->position }}</td>
                            <td>{{ $k->masa_kerja }}</td>
                            <td><span class="status-pill {{ $k->status }}">{{ ucfirst($k->status) }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('hr.karyawan.show', $k) }}" class="action-view">Detail</a>
                                    <a href="{{ route('hr.karyawan.edit', $k) }}" class="action-edit">Edit</a>
                                    <form method="POST" action="{{ route('hr.karyawan.destroy', $k) }}" onsubmit="return confirm('Hapus data {{ $k->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>

@endsection
