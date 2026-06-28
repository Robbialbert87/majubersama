@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('subtitle', 'Rekap penjualan telur per hari.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Tanggal</label><div class="form-input-wrapper"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()"></div></div>
</form>
<div class="card">
    <div class="card-header"><h2 class="card-title">Penjualan {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</h2></div>
    @if($sales->count() > 0)
    @foreach($sales as $sale)
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-color);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <div>
                <strong>{{ $sale->nomor_invoice }}</strong>
                @if($sale->customer) &mdash; {{ $sale->customer }}@endif
            </div>
            <div style="font-weight:700;color:var(--gain);">Rp {{ number_format($sale->details->sum('subtotal'),0,',','.') }}</div>
        </div>
        <table class="market-table"><thead><tr><th>Kategori</th><th>Ikat</th><th>Papan</th><th>Harga/Butir</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($sale->details as $d)
            <tr><td>{{ $d->eggCategory->kode }} - {{ $d->eggCategory->nama }}</td><td>{{ $d->ikat }}</td><td>{{ $d->papan }}</td><td>Rp {{ number_format($d->harga_per_butir,0,',','.') }}</td><td>Rp {{ number_format($d->subtotal,0,',','.') }}</td></tr>
            @endforeach
        </tbody></table>
    </div>
    @endforeach
    <div style="padding:16px 24px;background:var(--bg-card-hover);font-size:16px;">
        <div style="display:flex;justify-content:space-between;font-weight:700;">
            <span>Total Penjualan</span>
            <span style="color:var(--gain);">Rp {{ number_format($totalPenjualan,0,',','.') }}</span>
        </div>
    </div>
    @else
    <div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada penjualan pada tanggal ini.</div>
    @endif
</div>
@push('styles')<style>.market-table th,.market-table td{text-align:center}@media(max-width:768px){.market-table th,.market-table td{text-align:left}}</style>@endpush
@endsection
