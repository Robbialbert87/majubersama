@extends('layouts.admin')
@section('title', 'Laporan Produksi Bulanan')
@section('subtitle', 'Rekap produksi telur per bulan.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Bulan</label><div class="form-input-wrapper"><input type="month" name="bulan" class="form-input" value="{{ $bulan }}" onchange="this.form.submit()"></div></div>
</form>
@if($productions->count() > 0)
@php
    $grouped = $productions->groupBy(fn($p) => $p->barn_id);
    $grand = ['ikat'=>0,'papan'=>0,'sisa_butir'=>0];
@endphp
@foreach($grouped as $prods)
@php
    $barn = $prods->first()->barn;
    $barnTotal = ['ikat'=>0,'papan'=>0,'sisa_butir'=>0];
    foreach($prods as $p) {
        foreach($p->items as $item) {
            $barnTotal['ikat'] += $item->ikat;
            $barnTotal['papan'] += $item->papan;
            $barnTotal['sisa_butir'] += $item->sisa_butir;
        }
    }
    $grand['ikat'] += $barnTotal['ikat'];
    $grand['papan'] += $barnTotal['papan'];
    $grand['sisa_butir'] += $barnTotal['sisa_butir'];
@endphp
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h2 class="card-title">{{ $barn->kode ?? '-' }} — {{ $barn->nama ?? '-' }}</h2>
    </div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Tanggal</th><th>Ikat</th><th>Papan</th><th>Sisa Butir</th></tr></thead>
            <tbody>
                @foreach($prods as $p)
                @php $totalIkat = $p->items->sum('ikat'); $totalPapan = $p->items->sum('papan'); $totalSisa = $p->items->sum('sisa_butir'); @endphp
                <tr><td>{{ $p->tanggal->isoFormat('D MMM Y') }}</td><td>{{ $totalIkat }}</td><td>{{ $totalPapan }}</td><td>{{ $totalSisa }}</td></tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;">Subtotal {{ $barn->nama ?? '' }}</td>
                    <td>{{ $barnTotal['ikat'] }}</td>
                    <td>{{ $barnTotal['papan'] }}</td>
                    <td>{{ $barnTotal['sisa_butir'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($prods as $p)
        @php $totalIkat = $p->items->sum('ikat'); $totalPapan = $p->items->sum('papan'); $totalSisa = $p->items->sum('sisa_butir'); @endphp
        <div class="prod-card">
            <div class="prod-card-stats" style="margin-bottom:6px;">
                <div class="prod-stat"><span class="prod-stat-label">Tanggal</span><span class="prod-stat-val" style="font-size:12px;">{{ $p->tanggal->isoFormat('D MMM Y') }}</span></div>
            </div>
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $totalIkat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $totalPapan }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $totalSisa }}</span></div>
            </div>
        </div>
        @endforeach
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:2;"><span class="prod-stat-label">Subtotal {{ $barn->nama ?? '' }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $barnTotal['ikat'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $barnTotal['papan'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $barnTotal['sisa_butir'] }}</span></div>
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
                    <td class="total-label" style="text-align:center;">Total Semua Kandang</td>
                    <td>{{ $grand['ikat'] }}</td>
                    <td>{{ $grand['papan'] }}</td>
                    <td>{{ $grand['sisa_butir'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view" style="margin-top:0;">
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Total Semua Kandang</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $grand['ikat'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $grand['papan'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $grand['sisa_butir'] }}</span></div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card"><div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada bulan ini.</div></div>
@endif
@push('styles')
<style>
.market-table th,.market-table td{text-align:center}
@media(min-width:769px){.mobile-view{display:none!important}}
@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}
</style>
@endpush
@endsection
