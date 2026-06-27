@extends('layouts.admin')
@section('title', 'Input Hasil Sortir')
@section('subtitle', 'Input manual jumlah butir telur per ukuran untuk setiap kandang.')
@section('content')

@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
@if($errors->any())<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>@endif

@push('styles')
<style>
.sortir-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:16px;overflow:hidden;max-width:700px;}
.sortir-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color);}
.sortir-body{padding:24px;}
.size-row{display:grid;grid-template-columns:90px 1fr 160px;gap:12px;align-items:center;padding:14px 0;border-bottom:1px solid var(--border-color);}
.size-row:last-child{border-bottom:none;}
.size-badge{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:10px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:16px;}
.size-label{font-weight:600;font-size:14px;color:var(--text-primary);}
.size-harga{font-size:12px;color:var(--text-secondary);margin-top:2px;}
.preview-box{background:var(--bg-card-hover);border-radius:8px;padding:8px 12px;font-size:12px;color:var(--text-secondary);min-height:52px;display:flex;flex-direction:column;gap:3px;}
.preview-box .papan{font-weight:600;color:var(--gain);font-size:13px;}
.preview-box .subtotal{color:var(--accent-gold);font-weight:700;}
.info-box{background:rgba(96,165,250,0.08);border:1px solid rgba(96,165,250,0.25);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--text-secondary);margin-bottom:20px;}
.info-box strong{color:var(--text-primary);}

@media (max-width: 768px) {
    .sortir-card { border-radius: 12px; max-width: 100%; }
    .sortir-header { padding: 16px; }
    .sortir-header h2 { font-size: 16px; }
    .sortir-header .btn { font-size: 12px; padding: 6px 12px; }
    .sortir-body { padding: 16px; }
    .info-box { font-size: 12px; padding: 10px 12px; }
    .form-grid { flex-direction: column; gap: 12px; }
    .form-grid .form-group { width: 100%; }
    
    .size-row {
        display: block;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
    }
    .size-row:last-child { margin-bottom: 0; }
    .size-row div:first-child {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .size-badge { width: 38px; height: 38px; font-size: 14px; }
    .size-harga { margin-top: 0; }
    
    .size-row div:nth-child(2) > div[style*="display:flex"] {
        display: flex !important;
        flex-direction: column;
        gap: 10px;
    }
    .size-row div:nth-child(2) > div[style*="display:flex"] > div {
        flex: none !important;
        display: grid;
        grid-template-columns: 38px 1fr;
        align-items: center;
        gap: 8px;
    }
    .size-row div:nth-child(2) > div[style*="display:flex"] > div label {
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        margin: 0 !important;
        padding: 0;
        color: var(--text-secondary);
    }
    .size-row div:nth-child(2) > div[style*="display:flex"] > div input {
        width: 100% !important;
        padding: 12px 14px !important;
        font-size: 15px;
        text-align: left;
        box-sizing: border-box;
    }
    .size-row div:nth-child(2) > div[style*="display:flex"] > div div[style*="font-size:10px"] {
        display: none !important;
    }
    
    .preview-box {
        margin-top: 12px;
        min-height: 40px;
        padding: 10px 12px;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    .preview-box .papan { font-size: 13px; }
    .preview-box .subtotal { font-size: 14px; }
    
    .sortir-body > div[style*="grid-template-columns:90px"] { display: none !important; }
    
    .sortir-body > div[style*="font-weight:600;font-size:14px"] {
        font-size: 13px !important;
    }
    
    .sortir-body > div[style*="margin-top:20px"] {
        padding: 12px 14px !important;
    }
    .sortir-body > div[style*="margin-top:20px"] span[style*="font-size:20px"] {
        font-size: 18px !important;
    }
    
    div[style*="margin-top:24px;display:flex"] {
        flex-direction: column-reverse;
    }
    div[style*="margin-top:24px;display:flex"] .btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}
</style>
@endpush

<div class="sortir-card">
    <div class="sortir-header">
        <h2 class="card-title" style="margin:0;">Input Hasil Sortir</h2>
        <a href="{{ route('production-details.index') }}" class="btn" style="margin:0;">Kembali</a>
    </div>
    <div class="sortir-body">
             {{-- Info cara hitung --}}
             <div class="info-box">
                 <strong>Cara Hitung:</strong>
                 1 papan = 30 butir &nbsp;|&nbsp; 1 ikat = 5 papan (150 butir)
                 &nbsp;|&nbsp; Sisa = sisa butir &nbsp;|&nbsp; Subtotal = jumlah butir × harga/butir
             </div>

        <form method="POST" action="{{ route('production-details.store') }}" id="formSortir">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Produksi</label>
                    <div class="form-input-wrapper">
                        <input type="date" name="tanggal" class="form-input" required id="inputTanggal" value="{{ $selectedTanggal }}" onchange="loadProduction()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kandang</label>
                    <div class="form-input-wrapper">
                        <select name="kandang_id" class="form-select" required id="inputKandang" onchange="loadProduction()">
                            <option value="">— Pilih Kandang —</option>
                            @foreach($kandangs as $k)
                            <option value="{{ $k->id }}" data-kode="{{ $k->kode }}" data-nama="{{ $k->nama }}" {{ $selectedKandang == $k->id ? 'selected' : '' }}>
                                {{ $k->kode }} — {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tabel Input Sortir --}}
            <div style="font-weight:600;font-size:14px;margin-bottom:12px;color:var(--text-primary);">Jumlah Sortir per Ukuran</div>
            <div style="display:grid;grid-template-columns:90px 1fr 160px;gap:12px;padding:8px 0;font-size:12px;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                <span>Ukuran</span><span>Input (Ikat / Papan / Butir)</span><span>Total Butir & Harga</span>
            </div>

            @foreach($sizes as $i => $s)
            @php 
                $hp = $hargaMap[$s->id] ?? 0; 
                $ex = $existingDetails[$s->id] ?? null;
            @endphp
            <div class="size-row">
                <div>
                    <div class="size-badge">{{ $s->kode }}</div>
                </div>
                <div>
                    <div class="size-label">{{ $s->nama ?? $s->kode }}</div>
                    <div class="size-harga" id="harga_label_{{ $s->id }}" style="{{ $hp == 0 ? 'color:var(--loss);' : 'color:var(--gain);' }}">
                        @if($hp > 0)
                            Rp {{ number_format($hp, 0, ',', '.') }}/butir
                        @else
                            ⚠ Belum ada harga
                        @endif
                    </div>
                    <input type="hidden" name="details[{{ $i }}][egg_size_id]" value="{{ $s->id }}">
                    <input type="hidden" name="details[{{ $i }}][harga_per_butir]" value="{{ $hp }}" id="harga_val_{{ $s->id }}">
                    
                    <div style="display:flex;gap:8px;margin-top:8px;">
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Ikat</label>
                            <input type="number" name="details[{{ $i }}][jumlah_ikat]" class="form-input sortir-ikat" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->jumlah_ikat > 0 ? $ex->jumlah_ikat : '' }}" data-size-id="{{ $s->id }}" id="ikat_{{ $s->id }}" oninput="hitungRow(this)">
                            <div id="ikat_conv_{{ $s->id }}" style="font-size:10px;color:var(--text-secondary);margin-top:4px;text-align:center;"></div>
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Papan</label>
                            <input type="number" name="details[{{ $i }}][jumlah_papan]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->jumlah_papan > 0 ? $ex->jumlah_papan : '' }}" data-size-id="{{ $s->id }}" id="papan_{{ $s->id }}" oninput="hitungRow(this)">
                             <div id="papan_conv_{{ $s->id }}" style="font-size:10px;color:var(--text-secondary);margin-top:4px;text-align:center;"></div>
                         </div>
                          <div style="flex:1;">
                              <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Butir</label>
                              <input type="number" name="details[{{ $i }}][jumlah_butir]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex ? $ex->jumlah_butir : '' }}" data-size-id="{{ $s->id }}" id="butir_{{ $s->id }}" oninput="hitungRow(this)">
                         </div>
                         <div style="flex:1;">
                             <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Sisa</label>
                             <input type="number" name="details[{{ $i }}][sisa_butir]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->sisa_butir > 0 ? $ex->sisa_butir : '' }}" data-size-id="{{ $s->id }}" id="sisa_{{ $s->id }}" oninput="hitungRow(this)">
                         </div>
                    </div>
                </div>
                <div class="preview-box" id="preview_{{ $s->id }}">
                    <span class="papan">— total butir</span>
                    <span class="subtotal" style="margin-top:auto;">Rp 0</span>
                </div>
            </div>
            @endforeach

            {{-- Total Subtotal --}}
            <div style="margin-top:20px;padding:14px 16px;background:var(--bg-card-hover);border-radius:10px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:600;color:var(--text-primary);">Total Nilai Sortir</span>
                <span style="font-size:20px;font-weight:700;color:var(--gain);" id="grandTotal">Rp 0</span>
            </div>

            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('production-details.index') }}" class="btn">Batal</a>
                <button type="submit" class="btn primary">Simpan Hasil Sortir</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
/* Sembunyikan spinner atas/bawah pada input number */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}
input[type=number] { -moz-appearance: textfield; }

/* Input focus */
.sortir-body input[type="number"]:focus {
    border-color: var(--accent-copper) !important;
    box-shadow: 0 0 0 2px rgba(184,115,51,0.2) !important;
    outline: none;
}

/* Preview update animation */
.preview-box span { transition: color 0.2s ease; }

/* Card harga indicator */
.size-harga.has-harga { color: var(--gain); }
.size-harga.no-harga { color: var(--loss); }
</style>
<script>
var hargaData = @json($hargaMap);

function fmt(n){return 'Rp '+parseInt(n).toLocaleString('id-ID')}

function hitungRow(el){
    var sizeId  = el.dataset.sizeId;
    var harga   = parseInt(document.getElementById('harga_val_'+sizeId).value) || 0;
    
    var val = parseFloat(el.value) || 0;
    
    var ikatEl = document.getElementById('ikat_'+sizeId);
    var papanEl = document.getElementById('papan_'+sizeId);
    var butirEl = document.getElementById('butir_'+sizeId);
    var sisaEl = document.getElementById('sisa_'+sizeId);
    
    function formatNum(n) { 
        var intN = Math.floor(n);
        return intN; 
    }

    var isEmpty = (el.value === '');

    // Auto-calculate others based on what was typed
    if (el.id.startsWith('ikat_')) {
        papanEl.value = isEmpty ? '' : formatNum(val * 5);
        butirEl.value = isEmpty ? '' : formatNum(val * 150);
    } else if (el.id.startsWith('papan_')) {
        ikatEl.value = isEmpty ? '' : formatNum(val / 5);
        butirEl.value = isEmpty ? '' : formatNum(val * 30);
    } else if (el.id.startsWith('butir_')) {
        papanEl.value = isEmpty ? '' : formatNum(val / 30);
        ikatEl.value = isEmpty ? '' : formatNum(val / 150);
    }
    
    var totalButir = parseInt(butirEl.value) || 0;
    var subtotal   = totalButir * harga;
    
    // Tampilkan di preview box
    var prev = document.getElementById('preview_'+sizeId);
    prev.innerHTML = '<span class="papan" style="font-size:11px;">'+totalButir+' butir total</span>'
        +'<span class="subtotal" style="margin-top:auto;">'+fmt(subtotal)+'</span>';
        
    // Update grand total
    hitungGrand();
}

function hitungGrand(){
    var total=0;
    document.querySelectorAll('input[id^="butir_"]').forEach(function(el){
        var sizeId = el.dataset.sizeId;
        var harga = parseInt(document.getElementById('harga_val_'+sizeId).value) || 0;
        var totalButir = parseInt(el.value) || 0;
        total += totalButir * harga;
    });
    document.getElementById('grandTotal').textContent = fmt(total);
}

function loadProduction(){
    var tgl = document.getElementById('inputTanggal').value;
    var kandang = document.getElementById('inputKandang').value;
    if(tgl) {
        var url=new URL(window.location.href);
        url.searchParams.set('tanggal', tgl);
        if(kandang) url.searchParams.set('kandang_id', kandang);
        window.location.href=url.toString();
    }
}

// Init hitung saat load – trigger dari field yang sudah terisi (edit mode)
document.querySelectorAll('input[id^="ikat_"], input[id^="papan_"], input[id^="butir_"]').forEach(function(el){
    if(el.value !== '' && el.value !== '0' && parseInt(el.value) > 0){
        hitungRow(el);
    }
});
</script>
@endpush
@endsection
