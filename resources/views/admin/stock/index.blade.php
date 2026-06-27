@extends('layouts.admin')
@section('title', 'Stok Gudang')
@section('subtitle', 'Stok telur per ukuran.')
@section('content')
<div style="display:flex;gap:8px;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;">
    <div></div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
        <div class="form-input-wrapper" style="margin:0;"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal ?? '' }}" style="padding:8px 12px;font-size:13px;"></div>
        <button type="submit" class="btn primary" style="margin:0;padding:8px 16px;font-size:13px;">Lihat</button>
        @if($tanggal)<a href="{{ route('stock.index') }}" class="btn" style="margin:0;padding:8px 16px;font-size:13px;">Saat Ini</a>@endif
    </form>
</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Stok @if($tanggal) per {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}@else Saat Ini @endif</h2></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Ukuran</th><th style="text-align:center;">Butir</th><th style="text-align:center;">Papan</th><th style="text-align:center;">Ikat</th><th style="text-align:center;">Sisa Butir</th>@if($tanggal)<th style="text-align:center;">Stok Sekarang</th>@endif</tr></thead>
    <tbody>@forelse($stocks as $st)<tr><td data-label="Ukuran"><div class="coin-cell"><span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;letter-spacing:0.3px;">{{ $st->eggSize->kode ?? '-' }}</span><div><div class="coin-name" style="font-size:14px;">{{ $st->eggSize->nama ?? '-' }}</div></div></div></td><td data-label="Butir" class="price-cell" style="font-weight:600;">{{ number_format($st->jumlah_butir,0,',','.') }}</td><td data-label="Papan" class="price-cell">{{ $st->jumlah_papan }}</td><td data-label="Ikat" class="price-cell">{{ $st->jumlah_ikat }}</td><td data-label="Sisa" class="price-cell">{{ $st->jumlah_butir % 30 }}</td>@if($tanggal)<td data-label="Stok Sekarang" class="price-cell">{{ number_format(($currentStocks[$st->egg_size_id] ?? (object)['jumlah_butir' => 0])->jumlah_butir,0,',','.') }}</td>@endif</tr>@empty<tr><td colspan="{{ $tanggal ? 7 : 6 }}" style="text-align:center;color:var(--text-secondary);padding:32px;">Belum ada stok. Input produksi terlebih dahulu.</td></tr>@endforelse</tbody></table>
</div>
@push('styles')<style>.form-input-wrapper{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;display:inline-flex}.form-input-wrapper .form-input{background:none;border:none;color:var(--text-primary);outline:none;width:auto}@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@endsection
