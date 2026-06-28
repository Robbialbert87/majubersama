@extends('layouts.admin')
@section('title', 'Laporan Produksi Mingguan')
@section('subtitle', 'Rekap produksi telur per minggu.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Dari</label><div class="form-input-wrapper"><input type="date" name="start" class="form-input" value="{{ $start }}"></div></div>
    <div class="form-group" style="margin:0;"><label class="form-label">Sampai</label><div class="form-input-wrapper"><input type="date" name="end" class="form-input" value="{{ $end }}"></div></div>
    <button type="submit" class="btn primary" style="margin:0;">Tampilkan</button>
</form>
<div class="card">
    <div class="card-header"><h2 class="card-title">Produksi {{ \Carbon\Carbon::parse($start)->isoFormat('D MMM Y') }} - {{ \Carbon\Carbon::parse($end)->isoFormat('D MMM Y') }}</h2></div>
    @if($productions->count() > 0)
    @php $grouped = $productions->groupBy(fn($p) => $p->barn_id); @endphp
    @foreach($grouped as $prods)
    @php $barn = $prods->first()->barn; @endphp
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-color);">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;">{{ $barn->kode ?? '-' }}</span>
            <span style="font-weight:600;">{{ $barn->nama ?? '' }}</span>
        </div>
        <div class="desktop-view"><table class="market-table"><thead><tr><th>Tanggal</th><th>Ikat</th><th>Papan</th><th>Sisa Butir</th></tr></thead>
        <tbody>
            @foreach($prods as $p)
            @php $totalIkat = $p->items->sum('ikat'); $totalPapan = $p->items->sum('papan'); $totalSisa = $p->items->sum('sisa_butir'); @endphp
            <tr><td>{{ $p->tanggal->isoFormat('D MMM Y') }}</td><td>{{ $totalIkat }}</td><td>{{ $totalPapan }}</td><td>{{ $totalSisa }}</td></tr>
            @endforeach
        </tbody></table></div>
        <div class="prod-card-grid mobile-view">@foreach($prods as $p)@php $totalIkat = $p->items->sum('ikat'); $totalPapan = $p->items->sum('papan'); $totalSisa = $p->items->sum('sisa_butir'); @endphp<div class="prod-card"><div class="prod-card-stats" style="margin-bottom:6px;"><div class="prod-stat"><span class="prod-stat-label">Tanggal</span><span class="prod-stat-val" style="font-size:12px;">{{ $p->tanggal->isoFormat('D MMM Y') }}</span></div></div><div class="prod-card-stats"><div class="prod-stat"><span class="prod-stat-label">Ikat</span><span class="prod-stat-val">{{ $totalIkat }}</span></div><div class="prod-stat"><span class="prod-stat-label">Papan</span><span class="prod-stat-val">{{ $totalPapan }}</span></div><div class="prod-stat"><span class="prod-stat-label">Sisa</span><span class="prod-stat-val">{{ $totalSisa }}</span></div></div></div>@endforeach</div>
    </div>
    @endforeach
    @else
    <div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada periode ini.</div>
    @endif
</div>
@push('styles')<style>.market-table th,.market-table td{text-align:center}@media(max-width:768px){.market-table th,.market-table td{text-align:left}}@media(min-width:769px){.mobile-view{display:none!important}}@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}</style>@endpush
@endsection
