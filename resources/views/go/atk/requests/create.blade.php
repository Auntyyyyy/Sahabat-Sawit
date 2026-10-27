@extends('layouts.go')

@section('page-title', 'Permintaan Baru')
@section('page-subtitle', 'Catat permintaan ATK dari karyawan')

@section('extra-style')
.item-row { display: grid; grid-template-columns: 1fr 120px auto; gap: .6rem; margin-bottom: .6rem; }
.item-row select, .item-row input {
    width: 100%; padding: .62rem .85rem; border: 1px solid var(--line); border-radius: 8px;
    font-size: .88rem; font-family: 'Inter', sans-serif; background: #fff;
}
.item-row select:focus, .item-row input:focus {
    outline: none; border-color: var(--pink); box-shadow: 0 0 0 3px rgba(214,71,143,.14);
}
@media (max-width: 640px) { .item-row { grid-template-columns: 1fr 90px auto; } }
@endsection

@section('content')

@if ($errors->any())
<div class="alert-error">
    <strong>Mohon periksa kembali:</strong>
    <ul style="margin:.4rem 0 0; padding-left:1.1rem;">
        @foreach (array_unique($errors->all()) as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="panel">
    <div class="panel-head">
        <h2>Form Permintaan ATK</h2>
        <a href="{{ route('go.atk-requests.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.atk-requests.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-field">
                <label>Pemohon</label>
                <select name="karyawan_id">
                    <option value="">— Pilih karyawan —</option>
                    @foreach($karyawans as $k)
                    <option value="{{ $k->id }}" @selected((string) old('karyawan_id') === (string) $k->id)>
                        {{ $k->name }} — {{ $k->division }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label>Tanggal Permintaan</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}">
            </div>

            <div class="form-field full">
                <label>Barang yang Diminta</label>
                <div id="itemRows"></div>
                <button type="button" id="addRowBtn" class="btn btn-secondary" style="margin-top:.3rem;">+ Tambah Barang</button>
            </div>

            <div class="form-field full">
                <label>Catatan</label>
                <textarea name="catatan" rows="2" placeholder="Keperluan atau keterangan tambahan (opsional)">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('go.atk-requests.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Permintaan</button>
        </div>
    </form>
</div>

@endsection

@section('extra-script')
<script>
    const ATK_ITEMS = @json($items->map(fn ($i) => [
        'id' => $i->id,
        'label' => "{$i->kode} — {$i->nama} (stok {$i->stok} {$i->satuan})",
    ]));

    const INITIAL_ROWS = @json(old('items', [['atk_item_id' => '', 'jumlah' => 1]]));

    const rowsEl = document.getElementById('itemRows');
    let rowIndex = 0;

    function addRow(data = {}) {
        const i = rowIndex++;
        const row = document.createElement('div');
        row.className = 'item-row';

        const select = document.createElement('select');
        select.name = `items[${i}][atk_item_id]`;
        select.required = true;
        select.add(new Option('— Pilih barang —', ''));
        ATK_ITEMS.forEach(it => {
            select.add(new Option(it.label, it.id, false, String(data.atk_item_id) === String(it.id)));
        });

        const qty = document.createElement('input');
        qty.type = 'number';
        qty.min = 1;
        qty.required = true;
        qty.name = `items[${i}][jumlah]`;
        qty.value = data.jumlah || 1;

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'btn btn-secondary';
        remove.textContent = 'Hapus';
        remove.addEventListener('click', () => {
            if (rowsEl.children.length > 1) row.remove();
        });

        row.append(select, qty, remove);
        rowsEl.appendChild(row);
    }

    document.getElementById('addRowBtn').addEventListener('click', () => addRow());
    INITIAL_ROWS.forEach(r => addRow(r));
</script>
@endsection