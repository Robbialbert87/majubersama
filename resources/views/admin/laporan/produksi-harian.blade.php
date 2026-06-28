@extends('layouts.admin')
@section('title', 'Laporan Produksi Harian')
@section('subtitle', 'Rekap produksi telur per hari.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Tanggal</label><div class="form-input-wrapper"><input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()"></div></div>
</form>
<div class="card">
    <div class="card-header"><h2 class="card-title">Produksi {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</h2></div>
    @if($productions->count() > 0)
    @foreach($productions as $p)
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-color);">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;">{{ $p->barn->kode ?? '-' }}</span>
            <span style="font-weight:600;">{{ $p->barn->nama ?? '' }}</span>
        </div>
        <table class="market-table"><thead><tr><th>Kategori</th><th>Ikat</th><th>Papan</th><th>Sisa Butir</th></tr></thead>
        <tbody>
            @foreach($p->items as $item)
            <tr><td>{{ $item->eggCategory->kode }} - {{ $item->eggCategory->nama }}</td><td>{{ $item->ikat }}</td><td>{{ $item->papan }}</td><td>{{ $item->sisa_butir }}</td></tr>
            @endforeach
        </tbody></table>
    </div>
    @endforeach
    <div style="padding:16px 24px;background:var(--bg-card-hover);">
        <div style="font-weight:700;">Grand Total: {{ $grandTotal['ikat'] }} ikat, {{ $grandTotal['papan'] }} papan, {{ $grandTotal['sisa_butir'] }} sisa butir</div>
    </div>
    @else
    <div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada tanggal ini.</div>
    @endif
</div>
@push('styles')<style>.market-table th,.market-table td{text-align:center}@media(max-width:768px){.market-table th,.market-table td{text-align:left}}</style>@endpush
@endsection
