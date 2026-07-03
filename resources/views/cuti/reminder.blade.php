@extends('layouts.app')

@section('title', 'Pengingat Cuti')
@section('breadcrumb', 'Pengingat Cuti')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pengingat Cuti Akademik</h1>
        <p class="page-subtitle">Daftar mahasiswa yang masa cutinya akan segera berakhir (≤ 30 hari)</p>
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
                <th>Mahasiswa</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Sisa Hari</th>
                <th>Status Notif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cutiRecords as $cuti)
            @php
                $sisaHari = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai), false);
                $isUrgent = $sisaHari <= 7;
            @endphp
            <tr>
                <td>
                    <div style="font-weight: 500;">{{ $cuti->mahasiswa->nama }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $cuti->mahasiswa->nim }}</div>
                </td>
                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                <td>
                    @if($sisaHari < 0)
                        <span style="color: var(--accent-danger); font-weight: 600;">Lewat {{ abs(intval($sisaHari)) }} hari</span>
                    @else
                        <span style="color: {{ $isUrgent ? 'var(--accent-danger)' : 'var(--text-primary)' }}; font-weight: 600;">
                            {{ intval($sisaHari) }} hari
                        </span>
                    @endif
                </td>
                <td>
                    @if($cuti->status_notif == 'terkirim')
                        <span class="badge badge-selesai">Terkirim</span>
                    @else
                        <span class="badge badge-menunggu">Belum</span>
                    @endif
                </td>
                <td>
                    <div class="row-actions">
                        @if($cuti->mahasiswa->no_whatsapp)
                            <button class="btn btn-whatsapp btn-sm" onclick="kirimWa({{ $cuti->id }}, '{{ $cuti->mahasiswa->no_whatsapp }}', '{{ $cuti->mahasiswa->nama }}', '{{ $cuti->mahasiswa->nim }}', {{ intval($sisaHari) }})">Kirim WA</button>
                        @endif
                        <form action="{{ route('cuti.aktif', $cuti->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-primary btn-sm" onclick="return confirm('Aktifkan kembali mahasiswa ini?')">Aktifkan Kembali</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: var(--text-muted);">Tidak ada mahasiswa yang masa cutinya akan segera berakhir.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $cutiRecords->links() }}
</div>

<!-- Invisible form to update notif status when WA button clicked -->
<form id="notifForm" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
    const templatePesan = {!! json_encode($template) !!};

    function kirimWa(cutiId, phone, nama, nim, sisaHari) {
        // Prepare message
        let pesan = templatePesan
            .replace('{nama_mahasiswa}', nama)
            .replace('{nim}', nim)
            .replace('{sisa_hari}', sisaHari);
            
        // Encode for URL
        const encodedPesan = encodeURIComponent(pesan);
        
        // Clean phone number (remove leading 0 or +, ensure it starts with country code)
        let cleanPhone = phone.replace(/\D/g, '');
        if(cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.substring(1);
        }
        
        // Open WA in new tab
        const waUrl = `https://wa.me/${cleanPhone}?text=${encodedPesan}`;
        window.open(waUrl, '_blank');
        
        // Mark as sent in DB
        const notifForm = document.getElementById('notifForm');
        notifForm.action = '/cuti/' + cutiId + '/notif';
        notifForm.submit();
    }
</script>
@endpush
