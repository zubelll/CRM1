@extends('layouts.app')

@section('title', 'Kontak Layanan')
@section('breadcrumb', 'Kontak Layanan')

@push('styles')
<style>
.wa-preview-container {
    background: #E5DDD5;
    border-radius: var(--radius-md);
    padding: 24px;
    height: 100%;
    min-height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    position: relative;
    overflow: hidden;
}
.wa-preview-bg {
    position: absolute;
    inset: 0;
    opacity: 0.06;
    background-image: url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath d="M20 20.5V18H0v-2h20v-2H0v-2h20v-2H0V8h20V6H0V4h20V2H0V0h22v20h2V0h2v20h2V0h2v20h2V0h2v20h2V0h2v20h2v2H20v-1.5zM0 20h2v20H0V20zm4 0h2v20H4V20zm4 0h2v20H8V20zm4 0h2v20h-2V20zm4 0h2v20h-2V20zm4 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2z" fill="%23000000" fill-opacity="1" fill-rule="evenodd"/%3E%3C/svg%3E');
}
.wa-bubble {
    background: #DCF8C6;
    padding: 12px 16px;
    border-radius: 8px;
    border-top-right-radius: 0;
    max-width: 90%;
    align-self: flex-end;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    position: relative;
    z-index: 2;
    white-space: pre-wrap;
    font-size: 14px;
    color: #303030;
    line-height: 1.4;
}
.wa-bubble::after {
    content: '';
    position: absolute;
    top: 0;
    right: -10px;
    border-width: 10px 10px 0 0;
    border-style: solid;
    border-color: #DCF8C6 transparent transparent transparent;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kontak Layanan</h1>
        <p class="page-subtitle">Kelola template pesan dan kontak WhatsApp admin</p>
    </div>
</div>

@if(session('success'))
<div class="login-alert" style="background: #ECFDF5; border-color: #A7F3D0; color: #065F46;">
    {{ session('success') }}
</div>
@endif

<div class="card table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Kontak & Jabatan</th>
                <th>No. WhatsApp</th>
                <th>Status</th>
                <th>Template Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kontaks as $k)
            <tr>
                <td>
                    <div style="font-weight: 600;">{{ $k->nama_kontak }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $k->jabatan }}</div>
                </td>
                <td style="font-family: monospace; color: var(--primary);">{{ $k->no_whatsapp }}</td>
                <td>
                    @if($k->is_active)
                        <span class="badge badge-aktif">Aktif (Default)</span>
                    @else
                        <span class="badge badge-menunggu">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <div style="font-size: 12px; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $k->pesan_template }}
                    </div>
                </td>
                <td>
                    <div class="row-actions">
                        <button class="row-action-btn" onclick="openEditModal({{ json_encode($k) }})">✏️</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada data kontak.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


<!-- Modal Edit -->
<div id="editKontakModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 800px; width: 90%;">
        <form id="editKontakForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Kontak Layanan</h3>
                <button type="button" class="modal-close" onclick="closeModal('editKontakModal')">&times;</button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div style="display: flex; flex-wrap: wrap;">
                    <!-- Form Kiri -->
                    <div style="flex: 1; min-width: 300px; padding: 24px; border-right: 1px solid var(--border-color);">
                        <input type="hidden" name="nama_kontak" id="edit_nama" value="Admin Prodi">
                        <input type="hidden" name="jabatan" id="edit_jabatan" value="Staf Layanan Administrasi">
                        <div class="form-group">
                            <label class="form-label">No. WhatsApp <span class="required">*</span></label>
                            <input type="text" name="no_whatsapp" id="edit_wa" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Template Pesan <span class="required">*</span></label>
                            <textarea name="pesan_template" id="editPesanTemplate" class="form-input" rows="6" required></textarea>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                <input type="checkbox" name="is_active" id="edit_active" value="1"> Set sebagai kontak default aktif
                            </label>
                        </div>
                    </div>
                    <!-- Preview Kanan -->
                    <div style="flex: 1; min-width: 300px; padding: 24px; background: #F8FAFC;">
                        <h4 style="font-size: 14px; margin-bottom: 16px; color: var(--text-muted);">Preview WhatsApp</h4>
                        <div class="wa-preview-container">
                            <div class="wa-preview-bg"></div>
                            <div class="wa-bubble" id="editWaBubble"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('editKontakModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update Kontak</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live Preview Auto Play Logic
    function updatePreview(sourceId, targetId) {
        const source = document.getElementById(sourceId);
        const target = document.getElementById(targetId);
        
        let text = source.value;
        if(text.trim() === '') {
            target.innerText = 'Mulai ketik pesan template untuk melihat preview...';
            return;
        }

        // Replace placeholders with dummy data for preview
        text = text.replace(/{nama_mahasiswa}/g, 'Budi Santoso');
        text = text.replace(/{nim}/g, '2021001001');
        text = text.replace(/{sisa_hari}/g, '5');

        target.innerText = text;
    }



    document.getElementById('editPesanTemplate').addEventListener('input', function() {
        updatePreview('editPesanTemplate', 'editWaBubble');
    });

    function openEditModal(k) {
        document.getElementById('editKontakForm').action = '/kontak/' + k.id;
        document.getElementById('edit_nama').value = k.nama_kontak;
        document.getElementById('edit_jabatan').value = k.jabatan;
        document.getElementById('edit_wa').value = k.no_whatsapp;
        document.getElementById('editPesanTemplate').value = k.pesan_template;
        document.getElementById('edit_active').checked = k.is_active;
        
        updatePreview('editPesanTemplate', 'editWaBubble');
        openModal('editKontakModal');
    }
</script>
@endpush
