@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('breadcrumb', 'Data Mahasiswa')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Mahasiswa</h1>
        <p class="page-subtitle">Kelola data mahasiswa prodi Sistem Informasi</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal('addMahasiswaModal')">
            + Tambah Mahasiswa
        </button>
    </div>
</div>

@if(session('success'))
<div class="login-alert" style="background: #ECFDF5; border-color: #A7F3D0; color: #065F46;">
    {{ session('success') }}
</div>
@endif

<!-- Filter & Search -->
<div class="card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('mahasiswa.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-ghost' }}">Semua ({{ $statusCounts['semua'] }})</a>
            <a href="{{ route('mahasiswa.index', ['status' => 'aktif']) }}" class="btn {{ request('status') == 'aktif' ? 'btn-primary' : 'btn-ghost' }}">Aktif ({{ $statusCounts['aktif'] }})</a>
            <a href="{{ route('mahasiswa.index', ['status' => 'cuti']) }}" class="btn {{ request('status') == 'cuti' ? 'btn-primary' : 'btn-ghost' }}">Cuti ({{ $statusCounts['cuti'] }})</a>
            <a href="{{ route('mahasiswa.index', ['status' => 'drop_out']) }}" class="btn {{ request('status') == 'drop_out' ? 'btn-primary' : 'btn-ghost' }}">Drop Out ({{ $statusCounts['drop_out'] }})</a>
        </div>
        <div>
            <form action="{{ route('mahasiswa.index') }}" method="GET" class="search-wrapper">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <span class="search-icon">🔍</span>
                <input type="text" name="search" class="form-input" placeholder="Cari NIM atau Nama..." value="{{ request('search') }}" onchange="this.form.submit()">
            </form>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Mahasiswa</th>
                <th>Angkatan/Smt</th>
                <th>Status</th>
                <th>Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $mhs)
            <tr>
                <td>
                    <div class="row-name">
                        <div class="row-avatar">{{ substr($mhs->nama, 0, 1) }}</div>
                        <div>
                            <div>{{ $mhs->nama }}</div>
                            <div class="row-nim">{{ $mhs->nim }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div>Angkatan {{ $mhs->angkatan }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Semester {{ $mhs->semester }}</div>
                </td>
                <td>
                    @if($mhs->status == 'aktif')
                        <span class="badge badge-aktif">Aktif</span>
                    @elseif($mhs->status == 'cuti')
                        <span class="badge badge-cuti">Cuti</span>
                    @elseif($mhs->status == 'drop_out')
                        <span class="badge badge-ditolak">Drop Out</span>
                    @else
                        <span class="badge badge-menunggu">Tanpa Keterangan</span>
                    @endif
                </td>
                <td>
                    @if($mhs->no_whatsapp)
                        <a href="https://wa.me/{{ $mhs->no_whatsapp }}" target="_blank" class="btn btn-whatsapp btn-xs">WhatsApp</a>
                    @else
                        <span style="color: var(--text-muted); font-size: 12px;">Tidak ada no WA</span>
                    @endif
                </td>
                <td>
                    <div class="row-actions">
                        <button class="row-action-btn" onclick="openEditModal({{ $mhs }})">✏️</button>
                        <button class="row-action-btn" onclick="openDeleteModal({{ $mhs->id }}, '{{ $mhs->nama }}')">🗑️</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted);">Tidak ada data mahasiswa ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $mahasiswas->withQueryString()->links() }}
</div>

<!-- Modal Tambah -->
<div id="addMahasiswaModal" class="modal-overlay">
    <div class="modal-content">
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Tambah Mahasiswa Baru</h3>
                <button type="button" class="modal-close" onclick="closeModal('addMahasiswaModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NIM <span class="required">*</span></label>
                    <input type="text" name="nim" class="form-input" required maxlength="20">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-input" required>
                </div>
                <div style="display: flex; gap: 16px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Angkatan <span class="required">*</span></label>
                        <input type="text" name="angkatan" class="form-input" required maxlength="4">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Semester <span class="required">*</span></label>
                        <input type="number" name="semester" class="form-input" required min="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-input form-select" required>
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Cuti Akademik</option>
                        <option value="drop_out">Drop Out</option>
                        <option value="tanpa_keterangan">Tanpa Keterangan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="no_whatsapp" class="form-input" placeholder="Contoh: 6281234567890">
                </div>
                <div style="display: flex; gap: 16px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" class="form-input" placeholder="0.00 - 4.00">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">SKS Tempuh <span class="required">*</span></label>
                        <input type="number" name="sks_tempuh" class="form-input" value="0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('addMahasiswaModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editMahasiswaModal" class="modal-overlay">
    <div class="modal-content">
        <form id="editMahasiswaForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Mahasiswa</h3>
                <button type="button" class="modal-close" onclick="closeModal('editMahasiswaModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NIM <span class="required">*</span></label>
                    <input type="text" name="nim" id="edit_nim" class="form-input" required maxlength="20">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-input" required>
                </div>
                <div style="display: flex; gap: 16px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Angkatan <span class="required">*</span></label>
                        <input type="text" name="angkatan" id="edit_angkatan" class="form-input" required maxlength="4">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Semester <span class="required">*</span></label>
                        <input type="number" name="semester" id="edit_semester" class="form-input" required min="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="edit_status" class="form-input form-select" required>
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Cuti Akademik</option>
                        <option value="drop_out">Drop Out</option>
                        <option value="tanpa_keterangan">Tanpa Keterangan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="no_whatsapp" id="edit_no_whatsapp" class="form-input">
                </div>
                <div style="display: flex; gap: 16px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" id="edit_ipk" class="form-input">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">SKS Tempuh <span class="required">*</span></label>
                        <input type="number" name="sks_tempuh" id="edit_sks_tempuh" class="form-input" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('editMahasiswaModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteMahasiswaModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 400px;">
        <form id="deleteMahasiswaForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h3 class="modal-title">Hapus Mahasiswa</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteMahasiswaModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data mahasiswa <strong id="delete_mahasiswa_nama"></strong>? Data yang telah dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('deleteMahasiswaModal')">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: var(--accent-danger);">Hapus Data</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEditModal(mahasiswa) {
        document.getElementById('editMahasiswaForm').action = '/mahasiswa/' + mahasiswa.id;
        document.getElementById('edit_nim').value = mahasiswa.nim;
        document.getElementById('edit_nama').value = mahasiswa.nama;
        document.getElementById('edit_angkatan').value = mahasiswa.angkatan;
        document.getElementById('edit_semester').value = mahasiswa.semester;
        document.getElementById('edit_status').value = mahasiswa.status;
        document.getElementById('edit_no_whatsapp').value = mahasiswa.no_whatsapp;
        document.getElementById('edit_ipk').value = mahasiswa.ipk;
        document.getElementById('edit_sks_tempuh').value = mahasiswa.sks_tempuh;
        
        openModal('editMahasiswaModal');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteMahasiswaForm').action = '/mahasiswa/' + id;
        document.getElementById('delete_mahasiswa_nama').innerText = nama;
        openModal('deleteMahasiswaModal');
    }
</script>
@endpush
