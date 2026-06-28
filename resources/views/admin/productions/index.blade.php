@extends('layouts.admin')
@section('title', 'Input Produksi')
@section('subtitle', 'Riwayat produksi telur harian per kandang.')
@section('content')
@if(session('success'))<div style="background:rgba(38,161,123,0.2);color:var(--gain);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('success') }}</div>@endif
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <h2 class="card-title" style="margin:0;">Riwayat Produksi</h2>
    <a href="{{ route('productions.create') }}" class="btn primary">+ Input Produksi</a>
</div>
@if($productions->count() > 0)
@php $grouped = $productions->groupBy(fn($p) => $p->barn_id); @endphp
@foreach($grouped as $barnId => $prods)
@php $barn = $prods->first()->barn; @endphp
<div class="card" style="margin-bottom:24px;">
    <div class="card-header card-header-flex">
        <div class="card-header-left">
            <span class="kandang-label">{{ $barn->kode ?? '?' }}</span>
            <h2 class="card-title" style="margin:0;">{{ $barn->nama ?? 'Tanpa Kandang' }}</h2>
        </div>
        <div class="card-header-right">
            @foreach($prods as $p)
            <div class="header-prod">
                <span class="header-prod-date">{{ $p->tanggal->format('d M Y') }}</span>
                <button class="btn-aksi hapus" title="Hapus" onclick="openDelete({{ $p->id }},'{{ $barn->kode ?? '' }} {{ $p->tanggal->format('d M Y') }}')">&#10005;</button>
            </div>
            @endforeach
        </div>
    </div>
    @php $grand = ['ikat'=>0,'papan'=>0,'sisa_butir'=>0]; @endphp
    @foreach($prods as $p)
        @foreach($p->items as $item)
            @php
                $grand['ikat'] += $item->ikat;
                $grand['papan'] += $item->papan;
                $grand['sisa_butir'] += $item->sisa_butir;
            @endphp
        @endforeach
    @endforeach
    <div style="overflow-x:auto;">
        <table class="market-table sortir-table">
            <thead><tr><th>Kategori</th><th class="num">Ikat</th><th class="num">Papan</th><th class="num">Sisa Butir</th></tr></thead>
            <tbody>
                @foreach($prods as $p)
                    @foreach($p->items as $item)
                    <tr>
                        <td>
                            <span class="size-badge-sm">{{ $item->eggCategory->kode ?? '-' }}</span>
                            <span class="size-name">{{ $item->eggCategory->nama ?? '-' }}</span>
                        </td>
                        <td class="num">{{ $item->ikat }}</td>
                        <td class="num">{{ $item->papan }}</td>
                        <td class="num">{{ $item->sisa_butir }}</td>
                    </tr>
                    @endforeach
                @endforeach
                <tr class="total-row">
                    <td class="total-label">TOTAL {{ $barn->kode ?? '' }}</td>
                    <td class="num total-val">{{ $grand['ikat'] }}</td>
                    <td class="num total-val">{{ $grand['papan'] }}</td>
                    <td class="num total-val">{{ $grand['sisa_butir'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endforeach
@else
<div class="card"><div style="text-align:center;color:var(--text-secondary);padding:48px 32px;"><div style="font-size:48px;margin-bottom:16px;opacity:0.4;">&#129371;</div><div style="font-size:16px;font-weight:600;margin-bottom:8px;">Belum Ada Data Produksi</div><div style="font-size:13px;">Klik "+ Input Produksi" untuk menambahkan produksi pertama.</div></div></div>
@endif
<div class="modal-overlay" id="modalHapus" onclick="if(event.target===this)closeModal('modalHapus')">
    <div class="modal" style="max-width:400px;"><div class="modal-header"><h2>Hapus Produksi</h2><button class="modal-close" onclick="closeModal('modalHapus')">&times;</button></div>
    <div class="modal-body"><p style="color:var(--text-secondary);margin:0;">Yakin ingin menghapus produksi <strong id="deleteInfo"></strong>? Stok akan disesuaikan.</p></div>
    <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('modalHapus')">Batal</button><form method="POST" id="deleteForm" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn danger">Ya, Hapus</button></form></div></div>
</div>
@push('styles')
<style>.card-header-flex{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}.card-header-left{display:flex;align-items:center;gap:12px}.card-header-right{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.kandang-label{display:inline-flex;align-items:center;justify-content:center;padding:4px 14px;border-radius:20px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:12px;letter-spacing:0.5px;flex-shrink:0}.header-prod{display:flex;align-items:center;gap:6px;background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:4px 10px 4px 14px}.header-prod-date{font-size:12px;font-weight:600;color:var(--text-primary);white-space:nowrap}.size-badge-sm{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:11px;flex-shrink:0}.size-name{font-size:13px;color:var(--text-primary);margin-left:8px}.sortir-table th{font-size:11px;padding:14px 12px;white-space:nowrap}.sortir-table th.num,.sortir-table td.num{text-align:right;font-variant-numeric:tabular-nums}.sortir-table td{padding:12px;vertical-align:middle;font-size:13px}.btn-aksi{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:none;border-radius:8px;font-size:14px;cursor:pointer;transition:all 0.2s ease;text-decoration:none}.btn-aksi.hapus{background:rgba(239,68,68,0.15);color:#ef4444}.btn-aksi.hapus:hover{background:rgba(239,68,68,0.3);transform:scale(1.1)}.total-row{background:var(--bg-card-hover)!important}.total-row td{padding:14px 12px!important;border-top:2px solid var(--border-color)}.total-label{font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-secondary)}.total-val{font-weight:700;font-size:14px;color:var(--text-primary)}.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}.modal-overlay.active{display:flex}.modal{background:var(--bg-card);border-radius:16px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;border:1px solid var(--border-color)}.modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color)}.modal-header h2{font-size:18px;font-weight:600;margin:0}.modal-close{background:none;border:none;color:var(--text-secondary);font-size:24px;cursor:pointer;padding:4px 8px;line-height:1}.modal-close:hover{color:var(--text-primary)}.modal-body{padding:24px}.modal-footer{display:flex;gap:12px;justify-content:flex-end;padding:16px 24px;border-top:1px solid var(--border-color)}@media(max-width:540px){.modal{max-width:100%;border-radius:12px}}</style>@endpush
@push('scripts')<script>function closeModal(id){document.getElementById(id).classList.remove('active')}function openDelete(id,info){document.getElementById('deleteForm').action='/productions/'+id;document.getElementById('deleteInfo').textContent=info;document.getElementById('modalHapus').classList.add('active')}</script>@endpush
@endsection
