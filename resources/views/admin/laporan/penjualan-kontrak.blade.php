@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('subtitle', 'Rekap penjualan (Besar/Sedang/Kecil) per hari.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Dari</label><div class="form-input-wrapper"><input type="date" name="start" class="form-input" value="{{ $start }}"></div></div>
    <div class="form-group" style="margin:0;"><label class="form-label">Sampai</label><div class="form-input-wrapper"><input type="date" name="end" class="form-input" value="{{ $end }}"></div></div>
    <button type="submit" class="btn primary" style="margin:0;">Tampilkan</button>
</form>
@if($contractSales->count() > 0)
@foreach($groupedByBarn as $barnId => $items)
@php $barn = $items->first()->barn; $barnTotal = $items->sum('total_penjualan'); @endphp
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h2 class="card-title">{{ $barn->kode ?? '-' }} — {{ $barn->nama ?? '-' }}</h2>
    </div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Ikat</th><th>Harga/Butir</th><th>Total Butir</th><th>Total Penjualan</th></tr></thead>
            <tbody>
                @foreach($items as $cs)
                <tr>
                    <td>{{ $cs->tanggal->isoFormat('D MMM Y') }}</td>
                    <td><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;font-weight:600;">{{ $cs->eggCategory->kode ?? '-' }}</span> {{ $cs->eggCategory->nama ?? '-' }}</td>
                    <td>{{ $cs->jumlah_ikat }}</td>
                    <td>Rp {{ number_format($cs->harga_per_butir, 0, ',', '.') }}</td>
                    <td>{{ number_format($cs->total_butir, 0, ',', '.') }}</td>
                    <td style="font-weight:700;color:var(--gain);">Rp {{ number_format($cs->total_penjualan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;" colspan="5">Subtotal {{ $barn->nama ?? '' }}</td>
                    <td class="num total-val" style="font-weight:700;color:var(--gain);">Rp {{ number_format($barnTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($items as $cs)
        <div class="prod-card">
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Tanggal</span><span class="prod-stat-val" style="font-size:12px;">{{ $cs->tanggal->isoFormat('D MMM Y') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Kategori</span><span class="prod-stat-val"><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:1px 6px;font-size:11px;font-weight:600;">{{ $cs->eggCategory->kode ?? '-' }}</span> {{ $cs->eggCategory->nama ?? '' }}</span></div>
            </div>
            <div class="prod-card-stats" style="margin-top:8px;">
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $cs->jumlah_ikat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Harga</span><span class="prod-stat-val">Rp{{ number_format($cs->harga_per_butir, 0, ',', '.') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total Butir</span><span class="prod-stat-val">{{ number_format($cs->total_butir, 0, ',', '.') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Penjualan</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp{{ number_format($cs->total_penjualan, 0, ',', '.') }}</span></div>
            </div>
        </div>
        @endforeach
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Subtotal {{ $barn->nama ?? '' }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp{{ number_format($barnTotal, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="card">
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <tbody>
                <tr class="total-row" style="background:var(--bg-card-hover);">
                    <td class="total-label" style="text-align:center;" colspan="5">Total Semua Kandang</td>
                    <td class="num total-val" style="color:var(--gain);">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view" style="margin-top:0;">
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Total Semua Kandang</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card"><div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada penjualan kontrak pada tanggal ini.</div></div>
@endif
@push('styles')
<style>
.market-table th,.market-table td{text-align:center}
@media(min-width:769px){.mobile-view{display:none!important}}
@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}
</style>
@endpush
@endsection
