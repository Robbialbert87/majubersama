@extends('layouts.admin')
@section('title', 'Laporan Stok Gudang')
@section('subtitle', 'Stok telur gudang saat ini.')
@section('content')
<div class="card">
    <div class="card-header"><h2 class="card-title">Stok Gudang Saat Ini</h2></div>
    <div class="table-wrap desktop-view"><table class="market-table"><thead><tr><th style="text-align:center;">Kategori</th><th style="text-align:center;">Ikat</th><th style="text-align:center;">Papan</th><th style="text-align:center;">Sisa Butir</th><th style="text-align:center;">Total Butir</th></tr></thead>
    <tbody>
    @forelse($categories as $cat)
    @php $st = $stocks->get($cat->id); @endphp
    <tr>
        <td><div class="coin-cell"><span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;">{{ $cat->kode }}</span><div><div class="coin-name" style="font-size:14px;">{{ $cat->nama }}</div></div></div></td>
        <td class="price-cell">{{ number_format($st ? $st->ikat : 0, 0, ',', '.') }}</td>
        <td class="price-cell">{{ $st ? $st->papan : 0 }}</td>
        <td class="price-cell">{{ $st ? $st->sisa_butir : 0 }}</td>
        <td class="price-cell" style="font-weight:600;color:var(--gain);">{{ $st ? number_format(($st->ikat * $butirPerPapan * 5) + ($st->papan * $butirPerPapan) + $st->sisa_butir, 0, ',', '.') : '0' }}</td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center;color:var(--text-secondary);padding:32px;">Belum ada stok.</td></tr>
    @endforelse
    </tbody></table></div>
    <div class="card-grid mobile-view">@forelse($categories as $cat)@php $st = $stocks->get($cat->id); @endphp<div class="item-card"><div class="card-row"><span class="card-label">Kategori</span><span class="card-value"><span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;">{{ $cat->kode }}</span> {{ $cat->nama }}</span></div><div class="card-row"><span class="card-label">Ikat</span><span class="card-value">{{ number_format($st ? $st->ikat : 0, 0, ',', '.') }}</span></div><div class="card-row"><span class="card-label">Papan</span><span class="card-value">{{ $st ? $st->papan : 0 }}</span></div><div class="card-row"><span class="card-label">Sisa Butir</span><span class="card-value">{{ $st ? $st->sisa_butir : 0 }}</span></div><div class="card-row"><span class="card-label">Total Butir</span><span class="card-value" style="font-weight:600;color:var(--gain);">{{ $st ? number_format(($st->ikat * $butirPerPapan * 5) + ($st->papan * $butirPerPapan) + $st->sisa_butir, 0, ',', '.') : '0' }}</span></div></div>@empty<div style="text-align:center;color:var(--text-secondary);padding:24px;font-size:14px;">Belum ada stok.</div>@endforelse</div>
</div>
@push('styles')<style>.market-table th,.market-table td{text-align:center}@media(max-width:768px){.market-table th,.market-table td{text-align:left}}@media(min-width:769px){.mobile-view{display:none!important}}@media(max-width:768px){.desktop-view{display:none!important}.card-grid{display:flex;flex-direction:column;gap:10px;padding:4px 0}.item-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;padding:14px 16px;transition:all .2s ease}.item-card:active{transform:scale(0.99);opacity:0.9}.card-row{display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.04)}.card-row:last-of-type{border-bottom:none}.card-label{font-size:12px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.5px}.card-value{font-size:14px;font-weight:600;color:var(--text-primary)}.table-wrap .market-table{min-width:520px}.table-wrap .market-table td,.table-wrap .market-table th{padding:10px 6px;font-size:12px}.table-wrap .market-table th{font-size:11px;white-space:nowrap}}</style>@endpush
@endsection
