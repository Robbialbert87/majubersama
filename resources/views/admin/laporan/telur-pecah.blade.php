@extends('layouts.admin')
@section('title', 'Laporan Telur Pecah')
@section('subtitle', 'Rekap telur pecah per hari, minggu, dan bulan.')
@section('content')
<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div class="form-group" style="margin:0;"><label class="form-label">Dari</label><div class="form-input-wrapper"><input type="date" name="start" class="form-input" value="{{ $start }}"></div></div>
    <div class="form-group" style="margin:0;"><label class="form-label">Sampai</label><div class="form-input-wrapper"><input type="date" name="end" class="form-input" value="{{ $end }}"></div></div>
    <button type="submit" class="btn primary" style="margin:0;">Tampilkan</button>
</form>

@if($items->count() > 0)
{{-- Harian --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h2 class="card-title">Per Hari</h2></div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Tanggal</th><th>Kandang</th><th>Jumlah Butir</th></tr></thead>
            <tbody>
                @foreach($items as $i)
                <tr>
                    <td>{{ $i['tanggal']->isoFormat('D MMM Y') }}</td>
                    <td>{{ $i['barn_kode'] }}</td>
                    <td style="font-weight:600;">{{ number_format($i['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($items as $i)
        <div class="prod-card">
            <div class="prod-card-stats">
                <div class="prod-stat" style="flex:2;"><span class="prod-stat-label">Tanggal</span><span class="prod-stat-val" style="font-size:12px;">{{ $i['tanggal']->isoFormat('D MMM Y') }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Kandang</span><span class="prod-stat-val">{{ $i['barn_kode'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Butir</span><span class="prod-stat-val" style="font-weight:700;">{{ number_format($i['jumlah_butir'], 0, ',', '.') }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="table-wrap desktop-view" style="border-top:1px solid var(--border-color);">
        <table class="market-table">
            <tbody>
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;" colspan="2">Total Harian</td>
                    <td style="font-weight:700;color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mobile-view" style="padding:12px 16px;background:var(--bg-card-hover);border-top:1px solid var(--border-color);">
        <div style="display:flex;justify-content:space-between;font-weight:700;">
            <span>Total</span>
            <span style="color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'],0,',','.') }} butir</span>
        </div>
    </div>
</div>

{{-- Mingguan --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h2 class="card-title">Per Minggu</h2></div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Minggu</th><th>Jumlah Butir</th></tr></thead>
            <tbody>
                @foreach($weeklyTotals as $w)
                <tr>
                    <td>{{ $w['week_label'] }}</td>
                    <td style="font-weight:600;color:var(--gain);">{{ number_format($w['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;">Total</td>
                    <td style="font-weight:700;color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($weeklyTotals as $w)
        <div class="prod-card">
            <div class="prod-card-stats" style="margin-bottom:6px;">
                <div class="prod-stat" style="flex:3;"><span class="prod-stat-label">Minggu</span><span class="prod-stat-val" style="font-size:12px;">{{ $w['week_label'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Butir</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">{{ number_format($w['jumlah_butir'], 0, ',', '.') }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Bulanan --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h2 class="card-title">Per Bulan</h2></div>
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <thead><tr><th>Bulan</th><th>Jumlah Butir</th></tr></thead>
            <tbody>
                @foreach($monthlyTotals as $m)
                <tr>
                    <td>{{ $m['month_label'] }}</td>
                    <td style="font-weight:600;color:var(--gain);">{{ number_format($m['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="total-label" style="text-align:center;">Total</td>
                    <td style="font-weight:700;color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="prod-card-grid mobile-view">
        @foreach($monthlyTotals as $m)
        <div class="prod-card">
            <div class="prod-card-stats" style="margin-bottom:6px;">
                <div class="prod-stat" style="flex:3;"><span class="prod-stat-label">Bulan</span><span class="prod-stat-val" style="font-size:12px;">{{ $m['month_label'] }}</span></div>
                <div class="prod-stat"><span class="prod-stat-label">Butir</span><span class="prod-stat-val" style="color:var(--gain);font-weight:700;">{{ number_format($m['jumlah_butir'], 0, ',', '.') }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Grand Total --}}
<div class="card">
    <div class="table-wrap desktop-view">
        <table class="market-table">
            <tbody>
                <tr class="total-row" style="background:var(--bg-card-hover);">
                    <td class="total-label" style="text-align:center;font-size:15px;font-weight:700;" colspan="1">Total Telur Pecah ({{ \Carbon\Carbon::parse($start)->isoFormat('D MMM Y') }} - {{ \Carbon\Carbon::parse($end)->isoFormat('D MMM Y') }})</td>
                    <td style="font-size:16px;font-weight:700;color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'], 0, ',', '.') }} butir</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mobile-view" style="padding:16px;background:var(--bg-card-hover);">
        <div style="display:flex;justify-content:space-between;font-weight:700;font-size:15px;">
            <span>Total Telur Pecah</span>
            <span style="color:var(--gain);">{{ number_format($grandTotal['jumlah_butir'],0,',','.') }} butir</span>
        </div>
    </div>
</div>
@else
<div class="card"><div style="padding:48px 24px;text-align:center;color:var(--text-secondary);">Tidak ada data telur pecah pada periode ini.</div></div>
@endif
@push('styles')
<style>
.market-table th,.market-table td{text-align:center}
@media(min-width:769px){.mobile-view{display:none!important}}
@media(max-width:768px){.desktop-view{display:none!important}.prod-card-grid{display:flex;flex-direction:column;gap:6px}.prod-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:10px;padding:10px 12px}.prod-card-stats{display:flex;gap:8px}.prod-stat{display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;background:var(--bg-card-hover);border-radius:8px;padding:6px 4px}.prod-stat-label{font-size:10px;color:var(--text-secondary);font-weight:500;text-transform:uppercase;letter-spacing:0.3px}.prod-stat-val{font-size:13px;font-weight:600;color:var(--text-primary)}}
</style>
@endpush
@endsection
