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
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;">
        <div style="display:flex;align-items:center;gap:12px;">
            <span style="background:var(--accent-copper);color:#fff;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:600;">{{ $barn->kode ?? '-' }}</span>
            <span style="font-weight:600;">{{ $barn->nama ?? '' }}</span>
        </div>
        <div style="text-align:right;font-size:13px;">
            <div>{{ $totalIkat }} ikat</div>
            <div>{{ $totalPapan }} papan</div>
            <div>{{ $totalSisa }} sisa</div>
        </div>
    </div>
    @endforeach
    <div style="padding:16px 24px;background:var(--bg-card-hover);font-weight:700;">
        Grand Total: {{ $grand['ikat'] }} ikat, {{ $grand['papan'] }} papan, {{ $grand['sisa_butir'] }} sisa butir
    </div>
    @else
    <div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada produksi pada bulan ini.</div>
    @endif
</div>
@endsection
