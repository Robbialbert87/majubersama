@extends('layouts.admin')
@section('title', 'Laporan Penjualan Manual')
@section('subtitle', 'Rekap penjualan manual (Jumbo & Putih) per hari.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Tanggal</label><div class="form-input-wrapper"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()"></div></div>
</form>
@if($sales->count() > 0)
@foreach($sales as $sale)
@php $saleSubtotal = $sale->details->sum('subtotal'); @endphp
<div class="card" style="margin-bottom:24px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h2 class="card-title" style="margin:0;">{{ $sale->nomor_invoice }} @if($sale->customer) &mdash; {{ $sale->customer }}@endif</h2>
        <div style="font-weight:700;color:var(--gain);font-size:16px;">Rp {{ number_format($saleSubtotal,0,',','.') }}</div>
    </div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Kategori</th><th>Ikat</th><th>Papan</th><th>Harga/Butir</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($sale->details as $d)
                <tr>
                    <td><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;font-weight:600;">{{ $d->eggCategory->kode ?? '-' }}</span> {{ $d->eggCategory->nama ?? '-' }}</td>
                    <td>{{ $d->ikat }}</td>
                    <td>{{ $d->papan }}</td>
                    <td>Rp {{ number_format($d->harga_per_butir,0,',','.') }}</td>
                    <td style="font-weight:700;color:var(--gain);">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;" colspan="4">Subtotal {{ $sale->nomor_invoice }}</td>
                    <td class="num total-val" style="font-weight:700;color:var(--gain);">Rp {{ number_format($saleSubtotal,0,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($sale->details as $d)
        <div class="prod-card">
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Kategori</span><span class="prod-stat-val"><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:1px 6px;font-size:11px;font-weight:600;">{{ $d->eggCategory->kode ?? '-' }}</span> {{ $d->eggCategory->nama ?? '' }}</span></div>
            </div>
            <div class="prod-card-stats" style="margin-top:8px;">
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $d->ikat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $d->papan }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Harga</span><span class="prod-stat-val">Rp{{ number_format($d->harga_per_butir,0,',','.') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Subtotal</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp{{ number_format($d->subtotal,0,',','.') }}</span></div>
            </div>
        </div>
        @endforeach
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Subtotal {{ $sale->nomor_invoice }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp{{ number_format($saleSubtotal,0,',','.') }}</span></div>
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
                    <td class="total-label" style="text-align:center;" colspan="4">Total Penjualan Manual</td>
                    <td class="num total-val" style="color:var(--gain);">Rp {{ number_format($totalPenjualan,0,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view" style="margin-top:0;">
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Total Penjualan Manual</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">Rp {{ number_format($totalPenjualan,0,',','.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card"><div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada penjualan pada tanggal ini.</div></div>
@endif
@push('styles')
<style>
.market-table th,.market-table td{text-align:center}
@media(min-width:769px){.mobile-view{display:none!important}}
@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}
</style>
@endpush
@endsection
