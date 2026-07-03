@extends('layouts.app')

@section('title', 'Pengajuan Layanan')
@section('breadcrumb', 'Pengajuan Layanan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pengajuan Layanan</h1>
        <p class="page-subtitle">Kelola pengajuan layanan akademik mahasiswa</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal('addPengajuanModal')">
            + Tambah Pengajuan
        </button>
    </div>
</div>

@if(session('success'))
<div class="login-alert" style="background: #ECFDF5; border-color: #A7F3D0; color: #065F46;">
    {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Pengajuan</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Menunggu</div>
            <div class="stat-value" style="color: var(--accent-warning);">{{ $stats['menunggu'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Diproses</div>
            <div class="stat-value" style="color: var(--primary);">{{ $stats['diproses'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Selesai</div>
            <div class="stat-value" style="color: var(--accent-success);">{{ $stats['selesai'] }}</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Mahasiswa</th>
                <th>Jenis Layanan</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $p)
            <tr>
                <td>{{ $p->created_at->format('d M Y') }}</td>
                <td>
                    <div style="font-weight: 500;">{{ $p->mahasiswa->nama }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $p->mahasiswa->nim }}</div>
                </td>
                <td style="font-weight: 500;">{{ $p->jenis_layanan }}</td>
                <td>{{ Str::limit($p->keterangan, 50) }}</td>
                <td>
                    @if($p->status == 'menunggu')
                        <span class="badge badge-menunggu">Menunggu</span>
                    @elseif($p->status == 'diproses')
                        <span class="badge badge-diproses" style="background: #E0F2FE; color: #0369A1;">Diproses</span>
                    @elseif($p->status == 'selesai')
                        <span class="badge badge-selesai">Selesai</span>
                    @else
                        <span class="badge badge-ditolak">Ditolak</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-ghost" style="border: 1px solid var(--border-color);" onclick="openStatusModal({{ $p->id }}, '{{ $p->status }}')">Update Status</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: var(--text-muted);">Tidak ada data pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $pengajuans->links() }}
</div>

<!-- Modal Tambah -->
<div id="addPengajuanModal" class="modal-overlay">
    <div class="modal-content">
        <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Tambah Pengajuan Baru</h3>
                <button type="button" class="modal-close" onclick="closeModal('addPengajuanModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Mahasiswa <span class="required">*</span></label>
                    <select name="mahasiswa_id" class="form-input form-select" required>
                        <option value="">Pilih Mahasiswa...</option>
                        @foreach($mahasiswas as $m)
                            <option value="{{ $m->id }}">{{ $m->nim }} - {{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Layanan <span class="required">*</span></label>
                    <select name="jenis_layanan" class="form-input form-select" required>
                        <option value="Surat Keterangan Aktif">Surat Keterangan Aktif</option>
                        <option value="Pengajuan Cuti Akademik">Pengajuan Cuti Akademik</option>
                        <option value="Legalisasi Dokumen">Legalisasi Dokumen</option>
                        <option value="Permohonan Aktif Kembali">Permohonan Aktif Kembali</option>
                        <option value="Surat Pengantar Penelitian">Surat Pengantar Penelitian</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" class="form-input" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('addPengajuanModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Update Status -->
<div id="statusModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 400px;">
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h3 class="modal-title">Update Status Pengajuan</h3>
                <button type="button" class="modal-close" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Status Baru</label>
                    <select name="status" id="statusSelect" class="form-input form-select" required>
                        <option value="menunggu">Menunggu</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('statusModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openStatusModal(id, currentStatus) {
        document.getElementById('statusForm').action = '/pengajuan/' + id + '/status';
        document.getElementById('statusSelect').value = currentStatus;
        openModal('statusModal');
    }
</script>
@endpush
