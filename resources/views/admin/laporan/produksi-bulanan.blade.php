@extends('layouts.admin')
@section('title', 'Laporan Produksi Bulanan')
@section('subtitle', 'Rekap produksi telur per bulan.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Bulan</label><div class="form-input-wrapper"><input type="month" name="bulan" class="form-input" value="{{ $bulan }}" onchange="this.form.submit()"></div></div>
</form>
<div class="card">
    <div class="card-header"><h2 class="card-title">Produksi {{ \Carbon\Carbon::parse($bulan.'-01')->isoFormat('MMMM Y') }}</h2></div>
    @if($productions->count() > 0)
    @php
        $grouped = $productions->groupBy(fn($p) => $p->barn_id);
        $grand = ['ikat'=>0,'papan'=>0,'sisa_butir'=>0];
    @endphp
    @foreach($grouped as $prods)
    @php
        $barn = $prods->first()->barn;
        $totalIkat = $prods->sum(fn($p) => $p->items->sum('ikat'));
        $totalPapan = $prods->sum(fn($p) => $p->items->sum('papan'));
        $totalSisa = $prods->sum(fn($p) => $p->items->sum('sisa_butir'));
        $grand['ikat'] += $totalIkat;
        $grand['papan'] += $totalPapan;
        $grand['sisa_butir'] += $totalSisa;
    @endphp
    <div class="bulanan-row"><div class="bulanan-left"><span class="bulanan-badge">{{ $barn->kode ?? '-' }}</span><span class="bulanan-name">{{ $barn->nama ?? '' }}</span></div><div class="bulanan-right desktop-view"><span>{{ $totalIkat }} ikat</span><span>{{ $totalPapan }} papan</span><span>{{ $totalSisa }} sisa</span></div><div class="bulanan-stats mobile-view"><div class="bulanan-stat"><span class="bulanan-stat-label">Ikat</span><span class="bulanan-stat-val">{{ $totalIkat }}</span></div><div class="bulanan-stat"><span class="bulanan-stat-label">Papan</span><span class="bulanan-stat-val">{{ $totalPapan }}</span></div><div class="bulanan-stat"><span class="bulanan-stat-label">Sisa</span><span class="bulanan-stat-val">{{ $totalSisa }}</span></div></div></div>
    @endforeach
    <div class="bulanan-total">
        Grand Total: {{ $grand['ikat'] }} ikat, {{ $grand['papan'] }} papan, {{ $grand['sisa_butir'] }} sisa butir
    </div>
    @else
    <div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada bulan ini.</div>
    @endif
</div>
@push('styles')<style>.bulanan-row{display:flex;justify-content:space-between;align-items:center;padding:16px 24px;border-bottom:1px solid var(--border-color)}.bulanan-left{display:flex;align-items:center;gap:12px}.bulanan-badge{background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600}.bulanan-name{font-weight:600}.bulanan-right{display:flex;gap:16px;text-align:right;font-size:13px}.bulanan-total{padding:16px 24px;background:var(--bg-card-hover);font-weight:700}@media(min-width:769px){.mobile-view{display:none!important}}@media(max-width:768px){.desktop-view{display:none!important}.bulanan-row{flex-direction:column;align-items:stretch;gap:10px}.bulanan-stats{display:flex;gap:8px}.bulanan-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:8px 4px}.bulanan-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.bulanan-stat-val{font-size:14px;font-weight:700;color:var(--text-primary)}}</style>@endpush
@endsection
