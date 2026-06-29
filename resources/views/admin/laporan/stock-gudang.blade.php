@extends('layouts.admin')
@section('title', 'Laporan Stok Gudang')
@section('subtitle', 'Stok telur gudang per kandang.')
@section('content')
@if($barnStock->count() > 0)
@php $grandIkat = 0; $grandPapan = 0; $grandSisa = 0; $grandButir = 0; @endphp
@foreach($barnStock as $bs)
@php
    $barn = $bs['barn'];
    $items = $bs['items'];
    $barnIkat = 0; $barnPapan = 0; $barnSisa = 0; $barnButir = 0;
    foreach ($categories as $cat) {
        $d = $items[$cat->id] ?? ['ikat'=>0,'papan'=>0,'sisa_butir'=>0];
        $barnIkat += $d['ikat'];
        $barnPapan += $d['papan'];
        $barnSisa += $d['sisa_butir'];
        $barnButir += ($d['ikat'] * $butirPerPapan * 5) + ($d['papan'] * $butirPerPapan) + $d['sisa_butir'];
    }
    $grandIkat += $barnIkat; $grandPapan += $barnPapan; $grandSisa += $barnSisa; $grandButir += $barnButir;
@endphp
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h2 class="card-title">{{ $barn->kode ?? '-' }} — {{ $barn->nama ?? '-' }}</h2>
    </div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Kategori</th><th>Ikat</th><th>Papan</th><th>Sisa Butir</th><th>Total Butir</th></tr></thead>
            <tbody>
                @foreach($categories as $cat)
                @php $d = $items[$cat->id] ?? ['ikat'=>0,'papan'=>0,'sisa_butir'=>0]; $total = ($d['ikat'] * $butirPerPapan * 5) + ($d['papan'] * $butirPerPapan) + $d['sisa_butir']; @endphp
                <tr>
                    <td><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;font-weight:600;">{{ $cat->kode }}</span> {{ $cat->nama }}</td>
                    <td>{{ $d['ikat'] }}</td>
                    <td>{{ $d['papan'] }}</td>
                    <td>{{ $d['sisa_butir'] }}</td>
                    <td style="font-weight:600;color:var(--gain);">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;">Subtotal {{ $barn->nama ?? '' }}</td>
                    <td>{{ $barnIkat }}</td>
                    <td>{{ $barnPapan }}</td>
                    <td>{{ $barnSisa }}</td>
                    <td style="font-weight:700;color:var(--gain);">{{ number_format($barnButir, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($categories as $cat)
        @php $d = $items[$cat->id] ?? ['ikat'=>0,'papan'=>0,'sisa_butir'=>0]; @endphp
        <div class="prod-card">
            <div class="prod-card-stats">
                <div class="prod-stat"><span class="prod-stat-label">Kategori</span><span class="prod-stat-val"><span style="background:var(--accent-copper);color:#fff;border-radius:4px;padding:1px 6px;font-size:11px;font-weight:600;">{{ $cat->kode }}</span> {{ $cat->nama }}</span></div>
            </div>
            <div class="prod-card-stats" style="margin-top:8px;">
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $d['ikat'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $d['papan'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $d['sisa_butir'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">{{ number_format(($d['ikat'] * $butirPerPapan * 5) + ($d['papan'] * $butirPerPapan) + $d['sisa_butir'], 0, ',', '.') }}</span></div>
            </div>
        </div>
        @endforeach
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Subtotal {{ $barn->nama ?? '' }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $barnIkat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $barnPapan }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $barnSisa }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">{{ number_format($barnButir, 0, ',', '.') }}</span></div>
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
                    <td>{{ $grandIkat }}</td>
                    <td>{{ $grandPapan }}</td>
                    <td>{{ $grandSisa }}</td>
                    <td style="font-weight:600;color:var(--gain);">{{ number_format($grandButir, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view" style="margin-top:0;">
        <div class="prod-card prod-card-total">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:1;"><span class="prod-stat-label">Total Semua Kandang</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $grandIkat }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $grandPapan }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $grandSisa }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Total</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">{{ number_format($grandButir, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card"><div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Belum ada data produksi.</div></div>
@endif
@push('styles')
<style>
.market-table th,.market-table td{text-align:center}
@media(min-width:769px){.mobile-view{display:none!important}}
@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}
</style>
@endpush
@endsection
