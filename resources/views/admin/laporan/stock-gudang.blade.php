@extends('layouts.admin')
@section('title', 'Laporan Stok Gudang')
@section('subtitle', 'Stok telur gudang saat ini.')
@section('content')
<div class="card">
    <div class="card-header"><h2 class="card-title">Stok Gudang</h2></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Ukuran</th><th style="text-align:center;">Butir</th><th style="text-align:center;">Papan</th><th style="text-align:center;">Ikat</th><th style="text-align:center;">Sisa</th></tr></thead>
    <tbody>@forelse($stocks as $st)<tr><td data-label="Ukuran"><div class="coin-cell"><div class="coin-icon btc" style="font-size:13px;">{{ $st->eggSize->kode ?? '-' }}</div><div><div class="coin-name">{{ $st->eggSize->nama ?? '-' }}</div></div></div></td><td data-label="Butir" class="price-cell" style="font-weight:600;">{{ number_format($st->jumlah_butir,0,',','.') }}</td><td data-label="Papan" class="price-cell">{{ $st->jumlah_papan }}</td><td data-label="Ikat" class="price-cell">{{ $st->jumlah_ikat }}</td><td data-label="Sisa" class="price-cell">{{ $st->jumlah_butir % 30 }}</td></tr>@empty<tr><td colspan="5" style="text-align:center;color:var(--text-secondary);padding:32px;">Belum ada stok.</td></tr>@endforelse</tbody></table>
</div>
@push('styles')<style>@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@endsection
