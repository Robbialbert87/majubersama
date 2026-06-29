@extends('layouts.admin')
@section('title', 'Input Produksi')
@section('subtitle', 'Input hasil produksi telur per kategori untuk setiap kandang.')
@section('content')
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
@if($errors->any())<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>@endif
<div class="sortir-card">
    <div class="sortir-header">
        <h2 class="card-title" style="margin:0;">Input Produksi</h2>
        <a href="{{ route('productions.index') }}" class="btn" style="margin:0;">Kembali</a>
    </div>
    <div class="sortir-body">
        <div class="info-box">
            <strong>Cara Hitung:</strong>
            1 papan = {{ $butirPerPapan }} butir &nbsp;|&nbsp; 1 ikat = {{ $papanPerIkat }} papan ({{ $butirPerPapan * $papanPerIkat }} butir)
            &nbsp;|&nbsp; Sisa = sisa butir yang dibawa ke hari berikutnya
        </div>
        <form method="POST" action="{{ route('productions.store') }}" id="formProduksi">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Produksi</label>
                    <div class="form-input-wrapper">
                        <input type="date" name="tanggal" class="form-input" required id="inputTanggal" value="{{ $selectedTanggal }}" onchange="loadForm()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kandang</label>
                    <div class="form-input-wrapper">
                        <select name="barn_id" class="form-select" required id="inputBarn" onchange="loadForm()">
                            <option value="">— Pilih Kandang —</option>
                            @foreach($barns as $b)
                            <option value="{{ $b->id }}" {{ $selectedBarn == $b->id ? 'selected' : '' }}>
                                {{ $b->kode }} — {{ $b->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="section-divider">HASIL SORTIR</div>
            <div style="display:grid;grid-template-columns:90px 1fr;gap:12px;padding:8px 0;font-size:12px;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                <span>Kategori</span><span>Input (Ikat / Papan / Sisa Butir)</span>
            </div>
            @foreach($categories as $i => $cat)
            @php $ex = $existingItems[$cat->id] ?? null; @endphp
            <div class="size-row">
                <div>
                    <div class="size-badge">{{ $cat->kode }}</div>
                </div>
                <div>
                    <div class="size-label">{{ $cat->nama ?? $cat->kode }}</div>
                    <input type="hidden" name="items[{{ $i }}][egg_category_id]" value="{{ $cat->id }}">
                    <div style="display:flex;gap:8px;margin-top:8px;">
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Ikat</label>
                            <input type="number" name="items[{{ $i }}][ikat]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->ikat > 0 ? $ex->ikat : '' }}" id="ikat_{{ $cat->id }}">
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Papan</label>
                            <input type="number" name="items[{{ $i }}][papan]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->papan > 0 ? $ex->papan : '' }}" id="papan_{{ $cat->id }}">
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:11px;color:var(--text-secondary);display:block;margin-bottom:4px;">Sisa Butir</label>
                            <input type="number" name="items[{{ $i }}][sisa_butir]" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $ex && $ex->sisa_butir > 0 ? $ex->sisa_butir : '' }}" id="sisa_{{ $cat->id }}">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="section-divider" style="margin-top:24px;">KERUSAKAN</div>
            <div class="size-row">
                <div>
                    <div class="size-badge" style="background:var(--loss);">R</div>
                </div>
                <div>
                    <div class="size-label">Telur Pecah</div>
                    <div style="display:flex;gap:8px;margin-top:8px;max-width:200px;">
                        <div style="flex:1;">
                            <input type="number" name="pecah" class="form-input" style="padding:10px;" placeholder="0" min="0" value="{{ $existingPecah ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:24px;">
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <div class="form-input-wrapper">
                        <textarea name="catatan" class="form-input" style="min-height:70px;padding:12px;resize:vertical;" placeholder="Catatan (opsional)">{{ $existingCatatan ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('productions.index') }}" class="btn">Batal</a>
                <button type="submit" class="btn primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@push('styles')
<style>
.sortir-card{background:var(--bg-card);border:1px solid var(--border-color);border-radius:16px;overflow:hidden;max-width:700px;}
.sortir-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color);}
.sortir-body{padding:24px;}
.section-divider{font-weight:700;font-size:13px;color:var(--text-primary);margin-bottom:12px;padding:8px 0;border-bottom:1px solid var(--border-color);text-transform:uppercase;letter-spacing:1px;}
.size-row{display:grid;grid-template-columns:90px 1fr;gap:12px;align-items:center;padding:14px 0;border-bottom:1px solid var(--border-color);}
.size-row:last-child{border-bottom:none;}
.size-badge{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:10px;background:var(--accent-copper);color:#fff;font-weight:700;font-size:16px;}
.size-label{font-weight:600;font-size:14px;color:var(--text-primary);}
.info-box{background:rgba(96,165,250,0.08);border:1px solid rgba(96,165,250,0.25);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--text-secondary);margin-bottom:20px;}
.info-box strong{color:var(--text-primary);}
input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}
input[type=number]{-moz-appearance:textfield}
@media(max-width:768px){
    .sortir-card{border-radius:12px;max-width:100%}
    .sortir-header{padding:16px}
    .sortir-header h2{font-size:16px}
    .sortir-body{padding:16px}
    .info-box{font-size:12px;padding:10px 12px}
    .size-row{display:block;background:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;padding:16px;margin-bottom:12px}
    .size-row:last-child{margin-bottom:0}
    .size-row>div:first-child{display:flex;align-items:center;gap:10px;margin-bottom:12px}
    .size-badge{width:38px;height:38px;font-size:14px}
    .size-row div:nth-child(2)>div[style*="display:flex"]{flex-direction:column;gap:10px}
    .size-row div:nth-child(2)>div[style*="display:flex"]>div{grid-template-columns:38px 1fr;gap:8px;display:grid;align-items:center}
    .size-row div:nth-child(2)>div[style*="display:flex"]>div label{font-size:12px;font-weight:600;white-space:nowrap;margin:0!important;color:var(--text-secondary)}
    .size-row div:nth-child(2)>div[style*="display:flex"]>div input{width:100%!important;padding:12px 14px!important;font-size:15px;text-align:left;box-sizing:border-box}
    .sortir-body>div[style*="font-weight:600;font-size:14px"]{font-size:13px!important}
    div[style*="margin-top:24px;display:flex"]{flex-direction:column-reverse}
    div[style*="margin-top:24px;display:flex"] .btn{width:100%;text-align:center;justify-content:center}
}
</style>
@endpush
@push('scripts')
<script>
function loadForm(){
    var tgl = document.getElementById('inputTanggal').value;
    var barn = document.getElementById('inputBarn').value;
    if(tgl){
        var url = new URL(window.location.href);
        url.searchParams.set('tanggal', tgl);
        if(barn) url.searchParams.set('barn_id', barn);
        window.location.href = url.toString();
    }
}
</script>
@endpush
@endsection