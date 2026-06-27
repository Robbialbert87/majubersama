@extends('layouts.admin')
@section('title', 'Laporan Keuangan Harian')
@section('subtitle', 'Rekapitulasi pendapatan harian dari hasil produksi.')
@section('content')
<div style="display:flex;gap:8px;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div></div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;"><div class="form-input-wrapper" style="margin:0;"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()" style="padding:8px 12px;font-size:13px;"></div></form>
</div>
@if($groups->count() > 0)
@foreach($groups as $namaKandang => $items)
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h2 class="card-title">{{ $namaKandang }}</h2></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Ukuran</th><th style="text-align:center;">Butir</th><th style="text-align:center;">Ikat</th><th style="text-align:center;">Harga/Butir</th><th style="text-align:center;">Nilai</th></tr></thead>
    <tbody>@foreach($items as $d)<tr><td data-label="Ukuran" class="volume-cell"><span class="coin-name">{{ $d->eggSize->kode ?? '-' }}</span></td><td data-label="Butir" class="price-cell">{{ number_format($d->jumlah_butir,0,',','.') }}</td><td data-label="Ikat" class="price-cell">{{ number_format($d->jumlah_ikat,0,',','.') }}</td><td data-label="Harga/Butir" class="price-cell" style="color:var(--gain);">Rp {{ number_format($d->harga_per_butir,0,',','.') }}</td><td data-label="Subtotal" class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($d->subtotal,0,',','.') }}</td></tr>@endforeach</tbody></table>
    <div style="padding:12px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;"><span style="color:var(--text-secondary);">Subtotal {{ $namaKandang }}</span><span style="font-weight:600;color:var(--gain);">Rp {{ number_format($items->sum('subtotal'),0,',','.') }}</span></div>
</div>
@endforeach
@else
<div class="card"><div class="card-body" style="padding:48px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada tanggal ini.</div></div>
@endif
@if($groups->count() > 0)
<div class="card" style="border:2px solid var(--accent-copper);">
    <div style="padding:16px 24px;display:flex;justify-content:space-between;align-items:center;"><span style="font-weight:600;font-size:16px;">Total Keseluruhan</span><span style="font-weight:700;font-size:18px;color:var(--gain);">Rp {{ number_format($total,0,',','.') }}</span></div>
</div>
@endif
@push('styles')<style>.form-input-wrapper{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;display:inline-flex}.form-input-wrapper .form-input{background:none;border:none;color:var(--text-primary);outline:none;width:auto}@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@endsection
