@extends('layouts.admin')
@section('title', 'Penjualan')
@section('subtitle', 'Riwayat penjualan telur.')
@section('content')
@if(session('success'))<div style="background:rgba(38,161,123,0.2);color:var(--gain);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('success') }}</div>@endif
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <h2 class="card-title" style="margin:0;">Riwayat Penjualan</h2>
    <a href="{{ route('sales.create') }}" class="btn primary">+ Penjualan Baru</a>
</div>
@if($sales->count() > 0)
@foreach($sales as $sale)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header card-header-flex">
        <div class="card-header-left">
            <span class="kandang-label">{{ $sale->tanggal->format('d/m') }}</span>
            <div>
                <h2 class="card-title" style="margin:0;font-size:16px;">{{ $sale->nomor_invoice }}</h2>
                <div style="font-size:12px;color:var(--text-secondary);">
                    {{ $sale->tanggal->isoFormat('D MMM Y') }}
                    @if($sale->customer) &bull; {{ $sale->customer }}@endif
                </div>
            </div>
        </div>
        <div class="card-header-right">
            <div style="font-size:16px;font-weight:700;color:var(--gain);margin-right:12px;">
                Rp {{ number_format($sale->details->sum('subtotal'),0,',','.') }}
            </div>
            <a href="{{ route('sales.create', ['tanggal' => $sale->tanggal->format('Y-m-d')]) }}" class="btn-aksi edit" title="Edit">&#9998;</a>
            <button class="btn-aksi hapus" title="Hapus" onclick="openDelete({{ $sale->id }},'{{ $sale->nomor_invoice }}')">&#10005;</button>
        </div>
    </div>
    <div class="desktop-view">
        <div style="overflow-x:auto;">
            <table class="market-table sortir-table">
                <thead><tr><th>Kategori</th><th class="num">Ikat</th><th class="num">Papan</th><th class="num">Harga/Butir</th><th class="num">Subtotal</th></tr></thead>
                <tbody>
                    @foreach($sale->details as $d)
                    <tr>
                        <td><span class="size-badge-sm">{{ $d->eggCategory->kode }}</span> <span class="size-name">{{ $d->eggCategory->nama }}</span></td>
                        <td class="num">{{ $d->ikat }}</td>
                        <td class="num">{{ $d->papan }}</td>
                        <td class="num harga">Rp {{ number_format($d->harga_per_butir,0,',','.') }}</td>
                        <td class="num nilai">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td class="total-label">TOTAL</td>
                        <td></td><td></td><td></td>
                        <td class="num total-val nilai">Rp {{ number_format($sale->details->sum('subtotal'),0,',','.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="prod-card-grid mobile-view">
        @php $totals = ['ikat'=>0,'papan'=>0,'subtotal'=>0]; @endphp
        @foreach($sale->details as $d)
            @php
                $totals['ikat'] += $d->ikat;
                $totals['papan'] += $d->papan;
                $totals['subtotal'] += $d->subtotal;
            @endphp
        <div class="prod-card">
            <div class="prod-card-top">
                <span class="size-badge-sm">{{ $d->eggCategory->kode }}</span>
                <span class="prod-card-title">{{ $d->eggCategory->nama }}</span>
            </div>
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $d->ikat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $d->papan }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Harga</span><span class="prod-stat-val" style="color:var(--gain);">Rp{{ number_format($d->harga_per_butir,0,',','.') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Subtotal</span><span class="prod-stat-val" style="color:var(--gain);">Rp{{ number_format($d->subtotal,0,',','.') }}</span></div>
            </div>
        </div>
        @endforeach
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Total Ikat</span><span class="prod-stat-val total">{{ $totals['ikat'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total Papan</span><span class="prod-stat-val total">{{ $totals['papan'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label" style="font-size:9px;">Grand Total</span><span class="prod-stat-val total">Rp{{ number_format($totals['subtotal'],0,',','.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endforeach
@else
<div class="card"><div style="text-align:center;color:var(--text-secondary);padding:48px 32px;"><div style="font-size:48px;margin-bottom:16px;opacity:0.4;">&#128722;</div><div style="font-size:16px;font-weight:600;margin-bottom:8px;">Belum Ada Penjualan</div><div style="font-size:13px;">Klik "+ Penjualan Baru" untuk menambahkan penjualan pertama.</div></div></div>
@endif
<div class="modal-overlay" id="modalHapus" onclick="if(event.target===this)closeModal('modalHapus')">
    <div class="modal" style="max-width:400px;"><div class="modal-header"><h2>Hapus Penjualan</h2><button class="modal-close" onclick="closeModal('modalHapus')">&times;</button></div>
    <div class="modal-body"><p style="color:var(--text-secondary);margin:0;">Yakin ingin menghapus penjualan <strong id="deleteInfo"></strong>? Stok akan dikembalikan.</p></div>
    <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('modalHapus')">Batal</button><form method="POST" id="deleteForm" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn danger">Ya, Hapus</button></form></div></div></div>
@push('styles')
<style>.card-header-flex{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}.card-header-left{display:flex;align-items:center;gap:12px}.card-header-right{display:flex;align-items:center;gap:8px}.kandang-label{display:inline-flex;align-items:center;justify-content:center;padding:4px 14px;border-radius:20px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:12px;letter-spacing:0.5px;flex-shrink:0}.size-badge-sm{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:11px;flex-shrink:0}.size-name{font-size:13px;color:var(--text-primary);margin-left:8px}.sortir-table th{font-size:11px;padding:14px 12px;white-space:nowrap}.sortir-table th.num,.sortir-table td.num{text-align:right;font-variant-numeric:tabular-nums}.sortir-table td{padding:12px;vertical-align:middle;font-size:13px}.sortir-table td.num.harga{color:var(--gain)}.sortir-table td.num.nilai{color:var(--gain);font-weight:600}.btn-aksi{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:none;border-radius:8px;font-size:14px;cursor:pointer;transition:all 0.2s;text-decoration:none}.btn-aksi.edit{width:auto;height:auto;padding:4px 8px;background:rgba(96,165,250,0.15);color:#60a5fa;font-size:12px;font-weight:600}.btn-aksi.edit:hover{background:rgba(96,165,250,0.3)}.btn-aksi.hapus{background:rgba(239,68,68,0.15);color:#ef4444}.btn-aksi.hapus:hover{background:rgba(239,68,68,0.3);transform:scale(1.1)}.total-row{background:var(--bg-card-hover)!important}.total-row td{padding:14px 12px!important;border-top:2px solid var(--border-color)}.total-label{font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-secondary)}.total-val{font-weight:700;font-size:14px;color:var(--text-primary)}.total-val.nilai{color:var(--gain)}@media(min-width:769px){.mobile-view{display:none!important}}@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:8px;padding:12px 16px 16px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:12px;padding:12px 14px;transition:all .2s ease}.prod-card:active{transform:scale(0.99);opacity:0.9}.prod-card-top{display:flex;align-items:center;gap:8px;margin-bottom:8px}.prod-card-title{font-size:13px;font-weight:600;color:var(--text-primary);flex:1}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:8px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:700;color:var(--text-primary)}.prod-stat-val.total{color:var(--accent-copper)}.prod-card-total{border:2px solid var(--accent-copper)}.prod-card-total .prod-stat{background:rgba(214,155,98,0.1)}}.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}.modal-overlay.active{display:flex}.modal{background:var(--bg-card);border-radius:16px;width:100%;max-width:480px;border:1px solid var(--border-color)}.modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color)}.modal-header h2{font-size:18px;font-weight:600;margin:0}.modal-close{background:none;border:none;color:var(--text-secondary);font-size:24px;cursor:pointer;padding:4px 8px;line-height:1}.modal-body{padding:24px}.modal-footer{display:flex;gap:12px;justify-content:flex-end;padding:16px 24px;border-top:1px solid var(--border-color)}@media(max-width:540px){.modal{max-width:100%;border-radius:12px}}</style>@endpush
@push('scripts')<script>function closeModal(id){document.getElementById(id).classList.remove('active')}function openDelete(id,info){document.getElementById('deleteForm').action='/sales/'+id;document.getElementById('deleteInfo').textContent=info;document.getElementById('modalHapus').classList.add('active')}</script>@endpush
@endsection
