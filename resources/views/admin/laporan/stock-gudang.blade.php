@extends('layouts.admin')
@section('title', 'Laporan Stok Gudang')
@section('subtitle', 'Stok telur gudang saat ini.')
@section('content')
<div class="card">
    <div class="card-header"><h2 class="card-title">Stok Gudang Saat Ini</h2></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Kategori</th><th style="text-align:center;">Ikat</th><th style="text-align:center;">Papan</th><th style="text-align:center;">Sisa Butir</th><th style="text-align:center;">Total Butir</th></tr></thead>
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
    </tbody></table>
</div>
@push('styles')<style>.market-table th,.market-table td{text-align:center}@media(max-width:768px){.market-table th,.market-table td{text-align:left}}</style>@endpush
@endsection
