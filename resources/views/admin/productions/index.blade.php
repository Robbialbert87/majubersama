@extends('layouts.admin')
@section('title', 'Produksi Harian')
@section('subtitle', 'Riwayat produksi telur harian per kandang.')
@section('content')
@if(session('success'))<div style="background:rgba(38,161,123,0.2);color:var(--gain);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('success') }}</div>@endif
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
<div class="card">
    <div class="card-header"><h2 class="card-title">Riwayat Produksi Harian</h2></div>
    <div class="search-filters"><div class="search-box"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input type="text" id="searchInput" placeholder="Cari produksi..." oninput="filterTable()"></div></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Tanggal</th><th style="text-align:center;">Kandang</th><th style="text-align:center;">Jumbo</th><th style="text-align:center;">Besar</th><th style="text-align:center;">Sedang</th><th style="text-align:center;">Tanggung</th><th style="text-align:center;">Putih</th><th style="text-align:center;">Pecah</th><th style="text-align:center;">Sisa</th><th style="text-align:center;">Total</th></tr></thead>
    <tbody>@forelse($productions as $p)
        @php
            $j = $p->details->where('eggSize.kode', 'J')->sum('jumlah_butir');
            $b = $p->details->where('eggSize.kode', 'B')->sum('jumlah_butir');
            $s = $p->details->where('eggSize.kode', 'S')->sum('jumlah_butir');
            $t = $p->details->where('eggSize.kode', 'T')->sum('jumlah_butir');
            $putih = $p->details->where('eggSize.kode', 'P')->sum('jumlah_butir');
            $pecah = $p->details->where('eggSize.kode', 'PC')->sum('jumlah_butir');
            $sisa = $p->details->sum('sisa_butir');
            $total = $p->details->sum('jumlah_butir');
        @endphp
        <tr><td data-label="Tanggal"><div class="coin-cell"><div class="coin-icon btc" style="font-size:12px;">{{ $p->tanggal->format('d') }}</div><div><div class="coin-name">{{ $p->tanggal->format('d M Y') }}</div></div></div></td><td data-label="Kandang"><div class="coin-cell"><span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;letter-spacing:0.3px;">{{ $p->kandang->kode ?? '-' }}</span><div><div class="coin-name" style="font-size:14px;">{{ $p->kandang->nama ?? '' }}</div></div></div></td><td data-label="Jumbo" class="price-cell">{{ number_format($j,0,',','.') }}</td><td data-label="Besar" class="price-cell">{{ number_format($b,0,',','.') }}</td><td data-label="Sedang" class="price-cell">{{ number_format($s,0,',','.') }}</td><td data-label="Tanggung" class="price-cell">{{ number_format($t,0,',','.') }}</td><td data-label="Putih" class="price-cell" style="color:var(--text-secondary);">{{ number_format($putih,0,',','.') }}</td><td data-label="Pecah" class="price-cell" style="color:var(--loss);">{{ number_format($pecah,0,',','.') }}</td><td data-label="Sisa" class="price-cell" style="color:var(--text-secondary);">{{ number_format($sisa,0,',','.') }}</td><td data-label="Total" class="price-cell" style="font-weight:600;color:var(--gain);">{{ number_format($total,0,',','.') }}</td></tr>@empty<tr><td colspan="10" style="text-align:center;color:var(--text-secondary);padding:32px;">Belum ada data produksi.</td></tr>@endforelse</tbody></table>
</div>
@push('styles')<style>@media(min-width:769px){.market-table td{text-align:center}}@media(max-width:768px){.market-table .coin-icon.btc,.market-table td[data-label="Kandang"] .coin-cell>span:first-child{display:none!important}.market-table tbody tr{cursor:default}}</style>@endpush
@push('scripts')<script>function filterTable(){var q=document.getElementById('searchInput').value.toLowerCase();document.querySelectorAll('.market-table tbody tr').forEach(function(r){r.style.display=!q||r.textContent.toLowerCase().includes(q)?'':'none'})}</script>@endpush
@endsection
