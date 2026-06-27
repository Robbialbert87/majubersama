@extends('layouts.admin')
@section('title', 'Hasil Sortir')
@section('subtitle', 'Riwayat hasil sortir telur.')
@section('content')
@if(session('success'))<div style="background:rgba(38,161,123,0.2);color:var(--gain);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('success') }}</div>@endif
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <h2 class="card-title" style="margin:0;">Riwayat Hasil Sortir</h2>
    <a href="{{ route('production-details.create') }}" class="btn primary">+ Sortir Baru</a>
</div>

@if($productions->count() > 0)
    @php
        $groupedProductions = $productions->groupBy(fn($p) => $p->kandang->id ?? 0);
    @endphp

    @foreach($groupedProductions as $kandangId => $kandangProductions)
        @php
            $kandang = $kandangProductions->first()->kandang;
            $kandangName = $kandang ? $kandang->nama : 'Tanpa Kandang';
        @endphp
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header card-header-flex">
                <div class="card-header-left">
                    <span class="kandang-label">{{ $kandang->kode ?? '?' }}</span>
                    <h2 class="card-title" style="margin:0;">{{ $kandangName }}</h2>
                </div>
                <div class="card-header-right">
                    @foreach($kandangProductions as $p)
                        @php
                            $editUrl = route('production-details.create', ['tanggal' => $p->tanggal->format('Y-m-d'), 'kandang_id' => $p->kandang_id]);
                            $deleteInfo = $kandang ? $kandang->kode : '';
                            $deleteInfo .= ' ' . $p->tanggal->format('d M Y');
                        @endphp
                        <div class="header-prod">
                            <span class="header-prod-date">{{ $p->tanggal->format('d M Y') }}</span>
                            <a href="{{ $editUrl }}" class="btn-aksi edit" title="Edit">&#9998;</a>
                            <button class="btn-aksi hapus" title="Hapus" onclick="openDelete({{ $p->id }},'{{ $deleteInfo }}')">&#10005;</button>
                        </div>
                    @endforeach
                </div>
            </div>
            @php
                $grandButir = 0;
                $grandPapan = 0;
                $grandIkat = 0;
                $grandNilai = 0;
            @endphp
            @foreach($kandangProductions as $p)
                @foreach($p->details as $d)
                    @php
                        $grandButir += $d->jumlah_butir;
                        $grandPapan += $d->jumlah_papan;
                        $grandIkat += $d->jumlah_ikat;
                        $grandNilai += $d->subtotal;
                    @endphp
                @endforeach
            @endforeach
            <div style="overflow-x:auto;">
                <table class="market-table sortir-table">
                    <thead>
                        <tr>
                            <th>Ukuran</th>
                            <th class="num">Butir</th>
                            <th class="num">Papan</th>
                            <th class="num">Ikat</th>
                            <th class="num">Harga</th>
                            <th class="num">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kandangProductions as $p)
                            @foreach($p->details as $d)
                                <tr>
                                    <td data-label="Ukuran">
                                        <span class="size-badge-sm">{{ $d->eggSize->kode ?? '-' }}</span>
                                        <span class="size-name">{{ $d->eggSize->nama ?? '-' }}</span>
                                    </td>
                                    <td data-label="Butir" class="num">{{ number_format($d->jumlah_butir,0,',','.') }}</td>
                                    <td data-label="Papan" class="num">{{ $d->jumlah_papan }}</td>
                                    <td data-label="Ikat" class="num">{{ $d->jumlah_ikat }}</td>
                                    <td data-label="Harga" class="num harga">Rp {{ number_format($d->harga_per_butir,0,',','.') }}</td>
                                    <td data-label="Nilai" class="num nilai">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="total-row">
                            <td class="total-label">TOTAL {{ $kandang ? $kandang->kode : '' }}</td>
                            <td class="num total-val">{{ number_format($grandButir,0,',','.') }}</td>
                            <td class="num total-val">{{ number_format($grandPapan,0,',','.') }}</td>
                            <td class="num total-val">{{ number_format($grandIkat,0,',','.') }}</td>
                            <td></td>
                            <td class="num total-val nilai">Rp {{ number_format($grandNilai,0,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@else
    <div class="card">
        <div style="text-align:center;color:var(--text-secondary);padding:48px 32px;">
            <div style="font-size:48px;margin-bottom:16px;opacity:0.4;">&#128230;</div>
            <div style="font-size:16px;font-weight:600;margin-bottom:8px;">Belum Ada Data Sortir</div>
            <div style="font-size:13px;">Klik "+ Sortir Baru" untuk menambahkan hasil sortir pertama.</div>
        </div>
    </div>
@endif

<div class="modal-overlay" id="modalHapus" onclick="if(event.target===this)closeModal('modalHapus')">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header">
            <h2>Hapus Sortir</h2>
            <button class="modal-close" onclick="closeModal('modalHapus')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color:var(--text-secondary);margin:0;">Yakin ingin menghapus sortir <strong id="deleteInfo"></strong>? Stok akan disesuaikan.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeModal('modalHapus')">Batal</button>
            <form method="POST" id="deleteForm" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn danger">Ya, Hapus</button></form>
        </div>
    </div>
</div>

@push('styles')
<style>
.sortir-table th {
    font-size: 11px;
    padding: 14px 12px;
    white-space: nowrap;
}
.sortir-table th.num {
    text-align: right;
}
.sortir-table td {
    padding: 12px;
    vertical-align: middle;
    font-size: 13px;
}
.sortir-table td.num {
    text-align: right;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
.sortir-table td.num.harga {
    color: var(--gain);
}
.sortir-table td.num.nilai {
    color: var(--gain);
    font-weight: 600;
}
</style>

<style>
.card-header-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.card-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.header-prod {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 4px 10px 4px 14px;
}
.header-prod-date {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
}
.header-prod .btn-aksi {
    width: 30px;
    height: 30px;
    font-size: 12px;
}
.tgl-day {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--accent-copper);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}
.tgl-full {
    font-size: 13px;
    color: var(--text-primary);
    font-weight: 500;
}
</style>

<style>
.kandang-label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 14px;
    border-radius: 20px;
    background: var(--accent-copper);
    color: #fff;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 0.5px;
    margin-right: 12px;
    flex-shrink: 0;
}
</style>

<style>
.size-badge-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: var(--accent-copper);
    color: #fff;
    font-weight: 700;
    font-size: 11px;
    flex-shrink: 0;
}
.size-name {
    font-size: 13px;
    color: var(--text-primary);
    margin-left: 8px;
}
</style>

<style>
.btn-aksi {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-aksi.edit {
    background: rgba(96,165,250,0.15);
    color: #60a5fa;
}
.btn-aksi.edit:hover {
    background: rgba(96,165,250,0.3);
    transform: scale(1.1);
}
.btn-aksi.hapus {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
}
.btn-aksi.hapus:hover {
    background: rgba(239,68,68,0.3);
    transform: scale(1.1);
}
</style>

<style>
.total-row {
    background: var(--bg-card-hover) !important;
}
.total-row:hover {
    background: var(--bg-card-hover) !important;
}
.total-row td {
    padding: 14px 12px !important;
    border-top: 2px solid var(--border-color);
}
.total-label {
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
}
.total-val {
    font-weight: 700;
    font-size: 14px;
    color: var(--text-primary);
}
.total-val.nilai {
    color: var(--gain);
}
</style>

<style>
@media (max-width: 768px) {
    .sortir-table thead { display: none; }
    .sortir-table tbody tr {
        display: block;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 12px;
        position: relative;
    }
    .sortir-table tbody tr.total-row {
        background: var(--bg-card-hover) !important;
        border-color: var(--accent-copper);
        border-width: 2px;
    }
    .sortir-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border: none;
        gap: 12px;
        text-align: right;
    }
    .sortir-table td::before {
        content: attr(data-label);
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        text-align: left;
    }
    .sortir-table td:last-child { padding-bottom: 0; }
    .sortir-table td.num { text-align: right; }
    
    .size-badge-sm { display: none; }
    
    .total-row td.total-label { font-size: 11px; }
    .total-row td.total-label::before { display: none; }
    .total-row td:first-child { padding-top: 12px !important; }
}
</style>

<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}
.modal-overlay.active { display: flex; }
.modal {
    background: var(--bg-card);
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--border-color);
}
.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modal-header h2 { font-size: 18px; font-weight: 600; margin: 0; }
.modal-close {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 24px;
    cursor: pointer;
    padding: 4px 8px;
    line-height: 1;
}
.modal-close:hover { color: var(--text-primary); }
.modal-body { padding: 24px; }
.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
}
@media (max-width: 540px) {
    .modal { max-width: 100%; border-radius: 12px; }
}
</style>
@endpush

@push('scripts')
<script>
function closeModal(id){document.getElementById(id).classList.remove('active')}
function openDelete(id,info){
    document.getElementById('deleteForm').action='/productions/'+id;
    document.getElementById('deleteInfo').textContent=info;
    document.getElementById('modalHapus').classList.add('active')
}
</script>
@endpush
@endsection