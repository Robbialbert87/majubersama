@extends('layouts.admin')
@section('title', 'Penjualan Baru')
@section('subtitle', 'Input penjualan telur. Harga otomatis dari harga aktif.')
@section('content')
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
@if($errors->any())<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>@endif
<div class="sortir-card">
    <div class="sortir-header">
        <h2 class="card-title" style="margin:0;">Penjualan Baru</h2>
        <a href="{{ route('sales.index') }}" class="btn" style="margin:0;">Kembali</a>
    </div>
    <div class="sortir-body">
        <form method="POST" action="{{ route('sales.store') }}" id="formSale">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <div class="form-input-wrapper">
                        <input type="date" name="tanggal" class="form-input" required value="{{ $selectedTanggal }}" id="inputTanggal">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Invoice</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="nomor_invoice" class="form-input" required value="{{ $nomorInvoice }}">
                    </div>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Customer</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="customer" class="form-input" placeholder="Nama pembeli (opsional)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="catatan" class="form-input" placeholder="Catatan (opsional)">
                    </div>
                </div>
            </div>
            <div style="font-weight:600;font-size:14px;margin:20px 0 12px;color:var(--text-primary);">Detail Penjualan</div>
            @php $idx = 0; @endphp
            @foreach($categories as $cat)
            @php
                $st = $stocks->get($cat->id);
                $stokIkat = $st ? $st->ikat : 0;
                $stokPapan = $st ? $st->papan : 0;
                $harga = $hargaMap[$cat->kode] ?? 0;
                $disabled = $stokIkat <= 0 && $stokPapan <= 0;
            @endphp
            <div class="size-row">
                <div>
                    <div class="size-badge">{{ $cat->kode }}</div>
                </div>
                <div>
                    <div class="size-label">{{ $cat->nama }}</div>
                    <div style="font-size:11px;color:var(--text-secondary);">
                        Stok: {{ $stokIkat }} ikat / {{ $stokPapan }} papan
                        &middot; Harga: Rp {{ number_format($harga,0,',','.') }}/butir
                        &middot; Jual per: {{ ucfirst($cat->unit_penjualan) }}
                    </div>
                    <div class="harga-info" style="font-size:11px;color:var(--gain);">
                        Subtotal: <span id="subtotal_{{ $cat->id }}">Rp 0</span>
                    </div>
                    <input type="hidden" name="details[{{ $idx }}][egg_category_id]" value="{{ $cat->id }}">
                    <input type="hidden" class="harga-input" data-kode="{{ $cat->kode }}" data-kategori="{{ $cat->id }}" value="{{ $harga }}">
                    <input type="hidden" class="unit-input" data-kategori="{{ $cat->id }}" value="{{ $cat->unit_penjualan }}">
                    <input type="hidden" class="butir-per-papan" value="{{ $butirPerPapan }}">
                    <div style="display:flex;gap:8px;margin-top:8px;" class="sale-inputs">
                        @if($cat->unit_penjualan === 'ikat')
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Ikat</label>
                            <input type="number" name="details[{{ $idx }}][ikat]" class="form-input sale-ikat" style="padding:10px;" placeholder="0" min="0" max="{{ $stokIkat }}" value="" data-kategori="{{ $cat->id }}" oninput="hitungSale(this)">
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">&nbsp;</label>
                            <input type="hidden" name="details[{{ $idx }}][papan]" value="0">
                        </div>
                        @elseif($cat->unit_penjualan === 'papan')
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Papan</label>
                            <input type="number" name="details[{{ $idx }}][papan]" class="form-input sale-papan" style="padding:10px;" placeholder="0" min="0" max="{{ $stokPapan }}" value="" data-kategori="{{ $cat->id }}" oninput="hitungSale(this)">
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">&nbsp;</label>
                            <input type="hidden" name="details[{{ $idx }}][ikat]" value="0">
                        </div>
                        @else
                        <div style="flex:1;color:var(--text-secondary);font-size:12px;padding:10px 0;">Tidak dijual</div>
                        @endif
                    </div>
                </div>
            </div>
            @php $idx++; @endphp
            @endforeach
            <div style="margin-top:20px;padding:14px 16px;background:var(--bg-card-hover);border-radius:10px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:600;color:var(--text-primary);">Total Penjualan</span>
                <span style="font-size:20px;font-weight:700;color:var(--gain);" id="grandTotal">Rp 0</span>
            </div>
            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('sales.index') }}" class="btn">Batal</a>
                <button type="submit" class="btn primary">Simpan Penjualan</button>
            </div>
        </form>
    </div>
</div>
@push('styles')
<style>
.sortir-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:16px;overflow:hidden;max-width:700px;}
.sortir-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color);}
.sortir-body{padding:24px;}
.size-row{display:grid;grid-template-columns:90px 1fr;gap:12px;padding:14px 0;border-bottom:1px solid var(--border-color);}
.size-row:last-child{border-bottom:none;}
.size-badge{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:10px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:16px;}
.size-label{font-weight:600;font-size:14px;color:var(--text-primary);}
input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}
input[type=number]{-moz-appearance:textfield}
@media(max-width:768px){
    .sortir-card{border-radius:12px;max-width:100%}
    .sortir-header{padding:16px}
    .sortir-body{padding:16px}
    .size-row{display:block;background:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;padding:16px;margin-bottom:12px}
    .size-row:last-child{margin-bottom:0}
    .size-badge{width:38px;height:38px;font-size:14px}
}
</style>
@endpush
@push('scripts')
<script>
function fmt(n){return 'Rp '+parseInt(n).toLocaleString('id-ID')}

function hitungSale(el){
    var catId = el.dataset.kategori;
    var harga = parseFloat(document.querySelector('.harga-input[data-kategori="'+catId+'"]').value) || 0;
    var unit = document.querySelector('.unit-input[data-kategori="'+catId+'"]').value;
    var butirPerPapan = parseInt(document.querySelector('.butir-per-papan').value) || 30;

    var ikat = parseFloat(document.querySelector('input.sale-ikat[data-kategori="'+catId+'"]')?.value) || 0;
    var papan = parseFloat(document.querySelector('input.sale-papan[data-kategori="'+catId+'"]')?.value) || 0;

    var totalButir = 0;
    if(unit === 'ikat') totalButir = ikat * butirPerPapan * 5;
    else if(unit === 'papan') totalButir = papan * butirPerPapan;

    var subtotal = totalButir * harga;
    document.getElementById('subtotal_'+catId).textContent = fmt(subtotal);

    // Grand total
    var grand = 0;
    document.querySelectorAll('[id^="subtotal_"]').forEach(function(el){
        var val = parseInt(el.textContent.replace(/[^0-9]/g,'')) || 0;
        grand += val;
    });
    document.getElementById('grandTotal').textContent = fmt(grand);
}
</script>
@endpush
@endsection
