@extends('layouts.admin')
@section('title', 'Laporan Produksi Bulanan')
@section('subtitle', 'Rekapitulasi produksi bulanan.')
@section('content')
<div style="display:flex;gap:8px;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div></div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;"><div class="form-input-wrapper" style="margin:0;"><input type="month" name="bulan" class="form-input" value="{{ $bulan }}" onchange="this.form.submit()" style="padding:8px 12px;font-size:13px;"></div></form>
</div>
@if($groups->count() > 0)
@foreach($groups as $namaKandang => $items)
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h2 class="card-title">{{ $namaKandang }}</h2></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Tanggal</th><th style="text-align:center;">Besar</th><th style="text-align:center;">Kecil</th><th style="text-align:center;">Total</th><th style="text-align:center;">Putih</th><th style="text-align:center;">Pecah</th></tr></thead>
    <tbody>@foreach($items as $p)<tr><td data-label="Tanggal"><div class="coin-cell"><div class="coin-icon btc" style="font-size:12px;">{{ $p->tanggal->format('d') }}</div><div><div class="coin-name" style="font-size:14px;">{{ $p->tanggal->format('d M') }}</div></div></div></td><td data-label="Besar" class="price-cell">{{ number_format($p->ayam_besar,0,',','.') }}</td><td data-label="Kecil" class="price-cell">{{ number_format($p->ayam_kecil,0,',','.') }}</td><td data-label="Total" class="price-cell" style="font-weight:600;">{{ number_format($p->total_produksi,0,',','.') }}</td><td data-label="Putih" class="price-cell">{{ $p->telur_putih }}</td><td data-label="Pecah" class="price-cell" style="color:var(--loss);">{{ $p->telur_pecah }}</td></tr>@endforeach</tbody></table>
    <div style="padding:12px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;"><span style="color:var(--text-secondary);">Subtotal {{ $namaKandang }}</span><span style="font-weight:600;">{{ number_format($items->sum('total_produksi'),0,',','.') }} butir</span></div>
</div>
@endforeach
@else
<div class="card"><div class="card-body" style="padding:48px;text-align:center;color:var(--text-secondary);">Tidak ada data produksi pada bulan ini.</div></div>
@endif
@if($groups->count() > 0)
<div class="card" style="border:2px solid var(--accent-copper);">
    <div style="padding:16px 24px;display:flex;justify-content:space-between;align-items:center;"><span style="font-weight:600;font-size:16px;">Total Keseluruhan</span><span style="font-weight:700;font-size:18px;color:var(--gain);">{{ number_format($groups->flatten()->sum('total_produksi'),0,',','.') }} butir</span></div>
</div>
@endif
@push('styles')<style>.form-input-wrapper{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;display:inline-flex}.form-input-wrapper .form-input{background:none;border:none;color:var(--text-primary);outline:none;width:auto}@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@endsection
