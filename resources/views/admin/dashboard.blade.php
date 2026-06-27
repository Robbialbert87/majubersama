@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, ' . auth()->user()->name . '!')
@section('content')

@push('styles')
<style>
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px}
.stat-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:16px;padding:22px 24px;display:flex;flex-direction:column;gap:8px;transition:transform .2s,box-shadow .2s}
.stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 32px rgba(0,0,0,.18)}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.stat-icon.blue{background:rgba(96,165,250,.15);color:#60a5fa}
.stat-icon.green{background:rgba(38,161,123,.15);color:var(--gain)}
.stat-icon.amber{background:rgba(245,158,11,.15);color:#f59e0b}
.stat-icon.rose{background:rgba(244,63,94,.15);color:#f43f5e}
.stat-icon.purple{background:rgba(167,139,250,.15);color:#a78bfa}
.stat-icon.copper{background:rgba(184,115,51,.15);color:var(--accent-copper)}
.stat-value{font-size:26px;font-weight:700;color:var(--text-primary);line-height:1}
.stat-label{font-size:13px;color:var(--text-secondary)}
.stat-sub{font-size:12px;color:var(--text-secondary);margin-top:2px}

.dash-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:28px}
@media(max-width:900px){.dash-grid{grid-template-columns:1fr}}

.chart-bars{display:flex;align-items:flex-end;gap:8px;height:120px;padding:8px 0}
.chart-bar-wrap{display:flex;flex-direction:column;align-items:center;flex:1;gap:4px}
.chart-bar{width:100%;background:var(--accent-gold);border-radius:4px 4px 0 0;transition:height .4s;min-height:4px}
.chart-label{font-size:10px;color:var(--text-secondary);white-space:nowrap}

.stock-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border-color)}
.stock-row:last-child{border-bottom:none}
.stock-badge{font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;background:var(--accent-copper);color:#fff}
.stock-butir{font-weight:700;color:var(--gain)}
.stock-papan{font-size:12px;color:var(--text-secondary)}

.recent-item{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border-color)}
.recent-item:last-child{border-bottom:none}
.recent-date{width:36px;height:36px;background:var(--accent-gold);border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#000;flex-shrink:0}
.recent-info{flex:1}
.recent-name{font-size:13px;font-weight:600;color:var(--text-primary)}
.recent-sub{font-size:12px;color:var(--text-secondary)}
.recent-val{font-size:13px;font-weight:700;color:var(--gain)}
</style>
@endpush

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="stat-value">{{ $produksiHariIni }}</div>
        <div class="stat-label">Produksi Hari Ini</div>
        <div class="stat-sub">{{ \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a8 8 0 00-8 8c0 5 8 12 8 12s8-7 8-12a8 8 0 00-8-8z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <div class="stat-value">{{ number_format($sortirHariIni, 0, ',', '.') }}</div>
        <div class="stat-label">Butir Sortir Hari Ini</div>
        <div class="stat-sub">{{ number_format(intdiv($sortirHariIni, 30), 0, ',', '.') }} papan, {{ $sortirHariIni % 30 }} butir sisa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
        <div class="stat-value">{{ 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Hari Ini</div>
        <div class="stat-sub">Dari hasil sortir</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg></div>
        <div class="stat-value">{{ number_format($totalStok, 0, ',', '.') }}</div>
        <div class="stat-label">Total Stok Gudang</div>
        <div class="stat-sub">{{ number_format(intdiv($totalStok, 150), 0, ',', '.') }} ikat total</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>
        <div class="stat-value">{{ $totalKandang }}</div>
        <div class="stat-label">Kandang Aktif</div>
        <div class="stat-sub">Terdaftar dalam sistem</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon copper"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a8 8 0 00-8 8c0 5 8 12 8 12s8-7 8-12a8 8 0 00-8-8z"/></svg></div>
        <div class="stat-value">{{ $totalUkuran }}</div>
        <div class="stat-label">Ukuran Telur</div>
        <div class="stat-sub">Kategori ukuran aktif</div>
    </div>
</div>

{{-- Chart & Stock Side by side --}}
<div class="dash-grid">
    {{-- Chart produksi 7 hari --}}
    <div class="card">
        <div class="card-header"><h2 class="card-title">Produksi 7 Hari Terakhir</h2></div>
        @php $maxButir = max($last7days->pluck('butir')->toArray() ?: [1]); @endphp
        <div style="padding:20px 24px;">
            <div class="chart-bars">
                @foreach($last7days as $day)
                @php $pct = $maxButir > 0 ? ($day['butir'] / $maxButir * 100) : 0; @endphp
                <div class="chart-bar-wrap">
                    <div style="font-size:10px;color:var(--text-secondary);margin-bottom:4px;">{{ $day['butir'] > 0 ? number_format($day['butir'],0,',','.') : '-' }}</div>
                    <div class="chart-bar" style="height:{{ $pct }}%;background:{{ $pct > 50 ? 'var(--gain)' : 'var(--accent-gold)' }};"></div>
                    <div class="chart-label">{{ $day['tanggal'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stok per ukuran --}}
    <div class="card">
        <div class="card-header"><h2 class="card-title">Stok Gudang</h2><a href="{{ route('stock.index') }}" style="font-size:13px;color:var(--accent-gold);">Lihat Detail →</a></div>
        <div style="padding:0 24px 16px;">
            @forelse($stocks as $s)
            <div class="stock-row">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="stock-badge">{{ $s->eggSize->kode ?? '-' }}</span>
                    <span style="font-size:13px;color:var(--text-secondary);">{{ $s->eggSize->nama ?? '-' }}</span>
                </div>
                <div style="text-align:right;">
                    <div class="stock-butir">{{ number_format($s->jumlah_butir, 0, ',', '.') }} butir</div>
                    <div class="stock-papan">{{ intdiv($s->jumlah_butir, 150) }} ikat {{ intdiv($s->jumlah_butir % 150, 30) }} papan</div>
                </div>
            </div>
            @empty
            <div style="padding:24px 0;text-align:center;color:var(--text-secondary);font-size:13px;">Belum ada data stok.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Harga Terkini & Produksi Terbaru --}}
<div class="dash-grid">
    {{-- Harga terkini --}}
    <div class="card">
        <div class="card-header"><h2 class="card-title">Harga Terkini per Ukuran</h2><a href="{{ route('daily-prices.index') }}" style="font-size:13px;color:var(--accent-gold);">Kelola →</a></div>
        <div style="padding:0 24px 16px;">
            @forelse($hargaTerkini as $h)
            <div class="stock-row">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="stock-badge">{{ $h->eggSize->kode ?? '-' }}</span>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">{{ $h->eggSize->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-secondary);">{{ \Carbon\Carbon::parse($h->tanggal)->isoFormat('D MMM Y') }}</div>
                    </div>
                </div>
                <div class="recent-val">Rp {{ number_format($h->harga_per_butir, 0, ',', '.') }}<span style="font-size:11px;font-weight:400;color:var(--text-secondary);">/butir</span></div>
            </div>
            @empty
            <div style="padding:24px 0;text-align:center;color:var(--text-secondary);font-size:13px;">Belum ada data harga.</div>
            @endforelse
        </div>
    </div>

    {{-- Produksi terbaru --}}
    <div class="card">
        <div class="card-header"><h2 class="card-title">Produksi Terbaru</h2><a href="{{ route('productions.index') }}" style="font-size:13px;color:var(--accent-gold);">Semua →</a></div>
        <div style="padding:0 24px 16px;">
            @forelse($produksiTerbaru as $p)
            <div class="recent-item">
                <div class="recent-date">{{ $p->tanggal->format('d') }}</div>
                <div class="recent-info">
                    <div class="recent-name">{{ $p->kandang->kode ?? '-' }} &mdash; {{ $p->kandang->nama ?? '' }}</div>
                    <div class="recent-sub">{{ $p->tanggal->isoFormat('D MMM Y') }} &bull; {{ number_format($p->total_produksi, 0, ',', '.') }} butir</div>
                </div>
                <div class="recent-val">Rp {{ number_format($p->details->sum('subtotal'), 0, ',', '.') }}</div>
            </div>
            @empty
            <div style="padding:24px 0;text-align:center;color:var(--text-secondary);font-size:13px;">Belum ada produksi.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection
