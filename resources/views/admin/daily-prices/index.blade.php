@extends('layouts.admin')
@section('title', 'Harga Berlaku')
@section('subtitle', 'Kelola harga telur per tanggal berlaku. Harga diinput hanya saat berubah.')
@section('content')
@if(session('success'))<div style="background:rgba(38,161,123,0.2);color:var(--gain);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('success') }}</div>@endif
@if(session('error'))<div style="background:rgba(194,120,120,0.2);color:var(--loss);padding:12px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;">{{ session('error') }}</div>@endif
<div class="card">
    <div class="card-header"><h2 class="card-title">Harga per Tanggal Berlaku</h2><div class="btn-group" style="margin:0;"><button class="btn primary" onclick="document.getElementById('modalTambah').classList.add('active')">+ Harga Baru</button></div></div>
    <div class="search-filters"><div class="search-box"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input type="text" id="searchInput" placeholder="Cari..." oninput="filterTable()"></div></div>
    <table class="market-table"><thead><tr><th style="text-align:center;">Tanggal Berlaku</th><th style="text-align:center;">Jumbo</th><th style="text-align:center;">Besar</th><th style="text-align:center;">Sedang</th><th style="text-align:center;">Kecil</th><th style="text-align:center;">Putih</th></tr></thead>
    <tbody>@forelse($prices as $p)<tr><td><div class="coin-cell"><div class="coin-icon btc" style="font-size:12px;">{{ $p->tanggal_berlaku->format('d') }}</div><div><div class="coin-name">{{ $p->tanggal_berlaku->format('d M Y') }}</div></div></div></td>
        <td class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->jumbo,0,',','.') }}</td>
        <td class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->besar,0,',','.') }}</td>
        <td class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->sedang,0,',','.') }}</td>
        <td class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->kecil,0,',','.') }}</td>
        <td class="price-cell" style="color:var(--gain);font-weight:600;">Rp {{ number_format($p->putih,0,',','.') }}</td>
        </td></tr>@empty<tr><td colspan="6" style="text-align:center;color:var(--text-secondary);padding:32px;">Belum ada data harga.</td></tr>@endforelse</tbody></table>
</div>
<div class="modal-overlay" id="modalTambah" onclick="if(event.target===this)closeModal('modalTambah')"><div class="modal" style="max-width:500px;"><div class="modal-header"><h2>Harga Baru</h2><button class="modal-close" onclick="closeModal('modalTambah')">&times;</button></div>
<form method="POST" action="{{ route('daily-prices.store') }}">@csrf<div class="modal-body">
<div class="form-group"><label class="form-label">Tanggal Berlaku</label><div class="form-input-wrapper"><input type="date" name="tanggal_berlaku" class="form-input" value="{{ now()->format('Y-m-d') }}" required></div></div>
<div class="form-row">
<div class="form-group"><label class="form-label">Jumbo (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="jumbo" class="form-input" min="0" required></div></div>
<div class="form-group"><label class="form-label">Besar (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="besar" class="form-input" min="0" required></div></div></div>
<div class="form-row">
<div class="form-group"><label class="form-label">Sedang (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="sedang" class="form-input" min="0" required></div></div>
<div class="form-group"><label class="form-label">Kecil (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="kecil" class="form-input" min="0" required></div></div></div>
<div class="form-group"><label class="form-label">Putih (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="putih" class="form-input" min="0" required></div></div></div>
<div class="modal-footer"><button type="button" class="btn" onclick="closeModal('modalTambah')">Batal</button><button type="submit" class="btn primary">Simpan</button></div></form></div></div>
<div class="modal-overlay" id="modalEdit" onclick="if(event.target===this)closeModal('modalEdit')"><div class="modal" style="max-width:500px;"><div class="modal-header"><h2>Edit Harga</h2><button class="modal-close" onclick="closeModal('modalEdit')">&times;</button></div>
<form method="POST" id="editForm">@csrf @method('PUT')<div class="modal-body">
<div class="form-group"><label class="form-label">Tanggal Berlaku</label><div class="form-input-wrapper"><input type="date" name="tanggal_berlaku" id="editTanggal" class="form-input" required></div></div>
<div class="form-row">
<div class="form-group"><label class="form-label">Jumbo (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="jumbo" id="editJumbo" class="form-input" min="0" required></div></div>
<div class="form-group"><label class="form-label">Besar (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="besar" id="editBesar" class="form-input" min="0" required></div></div></div>
<div class="form-row">
<div class="form-group"><label class="form-label">Sedang (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="sedang" id="editSedang" class="form-input" min="0" required></div></div>
<div class="form-group"><label class="form-label">Kecil (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="kecil" id="editKecil" class="form-input" min="0" required></div></div></div>
<div class="form-group"><label class="form-label">Putih (Rp/butir)</label><div class="form-input-wrapper"><input type="number" name="putih" id="editPutih" class="form-input" min="0" required></div></div></div>
<div class="modal-footer"><button type="button" class="btn" onclick="closeModal('modalEdit')">Batal</button><button type="submit" class="btn primary">Simpan Perubahan</button></div></form></div></div>
<div class="modal-overlay" id="modalHapus" onclick="if(event.target===this)closeModal('modalHapus')"><div class="modal" style="max-width:400px;"><div class="modal-header"><h2>Hapus Harga</h2><button class="modal-close" onclick="closeModal('modalHapus')">&times;</button></div>
<div class="modal-body"><p style="color:var(--text-secondary);margin:0;">Yakin ingin menghapus harga tanggal <strong id="deleteInfo"></strong>?</p></div>
<div class="modal-footer"><button type="button" class="btn" onclick="closeModal('modalHapus')">Batal</button><form method="POST" id="deleteForm" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn danger">Ya, Hapus</button></form></div></div></div>
@push('styles')<style>.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}.modal-overlay.active{display:flex}.modal{background:var(--bg-card);border-radius:16px;width:100%;border:1px solid var(--border-color)}.modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border-color)}.modal-header h2{font-size:18px;font-weight:600;margin:0}.modal-close{background:none;border:none;color:var(--text-secondary);font-size:24px;cursor:pointer;padding:4px 8px;line-height:1}.modal-body{padding:24px}.modal-footer{display:flex;gap:12px;justify-content:flex-end;padding:16px 24px;border-top:1px solid var(--border-color)}.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}@media(max-width:540px){.form-row{grid-template-columns:1fr}.modal{max-width:100%;border-radius:12px}}@media(min-width:769px){.market-table td{text-align:center}}</style>@endpush
@push('scripts')<script>function closeModal(id){document.getElementById(id).classList.remove('active')}function filterTable(){var q=document.getElementById('searchInput').value.toLowerCase();document.querySelectorAll('.market-table tbody tr').forEach(function(r){r.style.display=!q||r.textContent.toLowerCase().includes(q)?'':'none'})}function openEdit(id,tgl,jumbo,besar,sedang,kecil,putih){document.getElementById('editForm').action='/daily-prices/'+id;document.getElementById('editTanggal').value=tgl;document.getElementById('editJumbo').value=jumbo;document.getElementById('editBesar').value=besar;document.getElementById('editSedang').value=sedang;document.getElementById('editKecil').value=kecil;document.getElementById('editPutih').value=putih;document.getElementById('modalEdit').classList.add('active')}function openDelete(id,info){document.getElementById('deleteForm').action='/daily-prices/'+id;document.getElementById('deleteInfo').textContent=info;document.getElementById('modalHapus').classList.add('active')}</script>@endpush
@endsection
