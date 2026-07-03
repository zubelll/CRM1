@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')


<!-- Page Header -->
<div class="page-header">
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Ringkasan data akademik mahasiswa Prodi Sistem Informasi</p>
  </div>
  <div class="flex gap-8">
    <select class="form-select" style="width: 180px;">
      <option>Semester Ganjil 2024</option>
      <option>Semester Genap 2024</option>
      <option>Semester Ganjil 2025</option>
    </select>
  </div>
</div>

<!-- ========== STAT CARDS ========== -->
<div class="stat-grid">
  <!-- Aktif -->
  <div class="stat-card aktif animate-fade-in">
    <div class="stat-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <div class="stat-info">
      <div class="stat-label">Mahasiswa Aktif</div>
      <div class="stat-value">{{ number_format($stats['aktif']['count']) }}</div>
      <span class="stat-change {{ $stats['aktif']['dir'] }}">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5H7z"/></svg>
        {{ $stats['aktif']['change'] }}
      </span>
    </div>
  </div>

  <!-- Cuti -->
  <div class="stat-card cuti animate-fade-in" style="animation-delay: 0.05s;">
    <div class="stat-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-info">
      <div class="stat-label">Cuti Akademik</div>
      <div class="stat-value">{{ $stats['cuti']['count'] }}</div>
      <span class="stat-change {{ $stats['cuti']['dir'] }}">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5H7z"/></svg>
        {{ $stats['cuti']['change'] }}
      </span>
    </div>
  </div>

  <!-- Drop Out -->
  <div class="stat-card do animate-fade-in" style="animation-delay: 0.1s;">
    <div class="stat-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <div class="stat-info">
      <div class="stat-label">Drop Out</div>
      <div class="stat-value">{{ $stats['do']['count'] }}</div>
      <span class="stat-change {{ $stats['do']['dir'] }}">
        {{ $stats['do']['change'] }}
      </span>
    </div>
  </div>

  <!-- Tanpa Keterangan -->
  <div class="stat-card tanpa-ket animate-fade-in" style="animation-delay: 0.15s;">
    <div class="stat-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div class="stat-info">
      <div class="stat-label">Tanpa Keterangan</div>
      <div class="stat-value">{{ $stats['tanpa_keterangan']['count'] }}</div>
      <span class="stat-change {{ $stats['tanpa_keterangan']['dir'] }}">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5H7z"/></svg>
        {{ $stats['tanpa_keterangan']['change'] }}
      </span>
    </div>
  </div>
</div>

<!-- ========== CHARTS ROW ========== -->
<div class="chart-grid">
  <!-- Line Chart — Tren Mahasiswa -->
  <div class="chart-card">
    <div class="card-header">
      <h3 class="card-title">Tren Mahasiswa per Semester</h3>
      <select class="form-select" style="width: auto; padding: 6px 30px 6px 10px; font-size: 12px;">
        <option>3 Semester</option>
        <option selected>6 Semester</option>
        <option>12 Semester</option>
      </select>
    </div>
    <div class="chart-container">
      <canvas id="trendChart"></canvas>
    </div>
  </div>

  <!-- Doughnut Chart — Proporsi Status -->
  <div class="chart-card">
    <div class="card-header">
      <h3 class="card-title">Proporsi Status</h3>
    </div>
    <div class="chart-container" style="display:flex;align-items:center;justify-content:center;">
      <canvas id="statusChart"></canvas>
    </div>
  </div>
</div>

<!-- ========== BOTTOM ROW: Table + Reminder Widget ========== -->
<div style="display: grid; grid-template-columns: 1fr 360px; gap: 20px;">
  <!-- Table: Pengajuan Terbaru -->
  <div class="card" style="padding: 0; overflow: hidden;">
    <div class="card-header" style="padding: 16px 20px; margin-bottom: 0;">
      <h3 class="card-title">Pengajuan Terbaru</h3>
      <a href="/pengajuan" class="btn btn-ghost btn-sm">Lihat Semua →</a>
    </div>
    <div class="table-wrapper" style="box-shadow: none;">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jenis Layanan</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentPengajuan as $i => $p)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>
              <div class="row-name">
                <div class="row-avatar">{{ strtoupper(substr($p['nama'], 0, 1)) }}</div>
                {{ $p['nama'] }}
              </div>
            </td>
            <td><span class="row-nim">{{ $p['nim'] }}</span></td>
            <td>{{ $p['layanan'] }}</td>
            <td>
              <span class="badge badge-{{ $p['status'] }}">
                {{ ucfirst($p['status']) }}
              </span>
            </td>
            <td class="text-muted">{{ $p['tanggal'] }}</td>
            <td>
              <div class="row-actions">
                <button class="row-action-btn" data-tooltip="Detail">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Reminder Widget -->
  <div class="reminder-widget">
    <div class="reminder-widget-header">
      <span class="reminder-widget-title">
        ⏰ Pengingat Cuti
      </span>
      <span class="reminder-widget-count">{{ count($reminders) }}</span>
    </div>
    @foreach($reminders as $r)
    <div class="reminder-item">
      <div class="reminder-student">
        <div class="reminder-student-name">{{ $r['nama'] }}</div>
        <div class="reminder-student-days {{ $r['sisa'] <= 7 ? 'urgent' : 'warning' }}">
          Sisa {{ $r['sisa'] }} hari
        </div>
      </div>
      <button class="btn btn-whatsapp btn-xs" title="Kirim Notif WA">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.832-1.438A9.955 9.955 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
      </button>
    </div>
    @endforeach
    <div style="padding: 12px 20px; text-align: center;">
      <a href="/cuti" class="btn btn-ghost btn-sm" style="color: var(--primary);">Lihat Semua Pengingat →</a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('resources/js/dashboard-chart.js') }}"></script>
@endpush
