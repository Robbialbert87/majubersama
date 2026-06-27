@extends('layouts.admin')
@section('title', 'Laporan Harga Harian')
@section('subtitle', 'Rekapitulasi harga telur harian.')
@section('content')
<div class="card">
    <div class="card-header"><h2 class="card-title">Harga Harian</h2><form method="GET" style="display:flex;gap:8px;align-items:center;"><div class="form-input-wrapper" style="margin:0;"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()" style="padding:8px 12px;font-size:13px;"></div></form></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Ukuran</th><th style="text-align:center;">Harga/Butir</th></tr></thead>
    <tbody>@forelse($prices as $p)<tr><td data-label="Ukuran"><div class="coin-cell"><div class="coin-icon btc" style="font-size:13px;">{{ $p->eggSize->kode ?? '-' }}</div><div><div class="coin-name">{{ $p->eggSize->nama ?? '-' }}</div></div></div></td><td data-label="Harga" class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->harga_per_butir,0,',','.') }}</td></tr>@empty<tr><td colspan="2" style="text-align:center;color:var(--text-secondary);padding:32px;">Tidak ada data.</td></tr>@endforelse</tbody></table>
</div>
@push('styles')<style>.form-input-wrapper{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;display:inline-flex}.form-input-wrapper .form-input{background:none;border:none;color:var(--text-primary);outline:none;width:auto}@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@endsection
