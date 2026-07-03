<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="CRM Layanan Akademik Mahasiswa — Prodi Sistem Informasi">
  <title>CRM Akademik — @yield('title', 'Dashboard')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

  <!-- App CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @stack('styles')
</head>
<body>

  <!-- ========== TOPBAR ========== -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <div class="topbar-logo">
        <div class="topbar-logo-icon">🎓</div>
        <span>CRM Akademik</span>
      </div>
      <div class="topbar-breadcrumb">
        <span>/</span>
        <span>@yield('breadcrumb', 'Dashboard')</span>
      </div>
    </div>

    <div class="topbar-right">
      <!-- Notification Bell -->
      <button class="topbar-icon-btn" id="notifBell" aria-label="Notifikasi">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="notif-badge" id="notifCount">3</span>
      </button>

      <!-- Notification Dropdown -->
      <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-dropdown-header">
          <span>Notifikasi</span>
          <button class="btn btn-ghost btn-xs">Tandai semua dibaca</button>
        </div>
        <div class="notif-dropdown-body">
          <div class="notif-item unread">
            <div class="notif-icon" style="background: #D1FAE5;">📋</div>
            <div class="notif-content">
              <div class="notif-text">Pengajuan baru dari <strong>Budi Santoso</strong> — Surat Keterangan Aktif</div>
              <div class="notif-time">5 menit yang lalu</div>
            </div>
          </div>
          <div class="notif-item unread">
            <div class="notif-icon" style="background: #FEF3C7;">⏰</div>
            <div class="notif-content">
              <div class="notif-text"><strong>3 mahasiswa</strong> cuti akan berakhir dalam 7 hari</div>
              <div class="notif-time">1 jam yang lalu</div>
            </div>
          </div>
          <div class="notif-item">
            <div class="notif-icon" style="background: #DBEAFE;">✅</div>
            <div class="notif-content">
              <div class="notif-text">Pengajuan <strong>Legalisasi Dokumen</strong> telah selesai diproses</div>
              <div class="notif-time">3 jam yang lalu</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Avatar -->
      <div class="topbar-avatar" id="avatarMenu">
        <div class="avatar-circle">AD</div>
        <div class="avatar-info">
          <span class="avatar-name">{{ Auth::user()->name ?? 'Admin' }}</span>
          <span class="avatar-role">Administrator</span>
        </div>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
      </div>

      <!-- Avatar Dropdown -->
      <div class="avatar-dropdown" id="avatarDropdown">
        <a href="#">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Profil Saya
        </a>
        <a href="#">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          Pengaturan
        </a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- ========== MOBILE SIDEBAR OVERLAY ========== -->
  <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

  <!-- ========== SIDEBAR ========== -->
  <aside class="sidebar" id="sidebar">
    <nav class="sidebar-nav">
      <div class="sidebar-section-title">Menu Utama</div>

      <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </span>
        <span class="sidebar-nav-label">Dashboard</span>
      </a>

      <a href="{{ route('mahasiswa.index') }}" class="sidebar-nav-item {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </span>
        <span class="sidebar-nav-label">Data Mahasiswa</span>
      </a>

      <a href="{{ route('pengajuan.index') }}" class="sidebar-nav-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </span>
        <span class="sidebar-nav-label">Pengajuan Layanan</span>
      </a>

      <div class="sidebar-section-title">Manajemen</div>

      <a href="{{ route('cuti.index') }}" class="sidebar-nav-item {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
        <span class="sidebar-nav-label">Pengingat Cuti</span>
      </a>

      <a href="{{ route('kontak.index') }}" class="sidebar-nav-item {{ request()->routeIs('kontak.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </span>
        <span class="sidebar-nav-label">Kontak Layanan</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-nav-item" style="width:100%;border:none;background:transparent;cursor:pointer;font-size:14px;">
          <span class="sidebar-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </span>
          <span class="sidebar-nav-label">Keluar</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- ========== CONTENT ========== -->
  <main class="content-wrapper">
    <div class="content-area">
      @yield('content')
    </div>
  </main>

  <!-- App JS -->
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
