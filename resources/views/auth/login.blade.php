{{-- Login Page — Full screen split layout --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Login — CRM Layanan Akademik Mahasiswa Prodi Sistem Informasi">
  <title>CRM Akademik — Login</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="background: var(--bg-card);">

  <div class="login-wrapper">
    <!-- ========== LEFT: BRANDING ========== -->
    <div class="login-branding">
      <div class="login-branding-content">
        <div class="login-brand-icon">🎓</div>
        <h1 class="login-brand-title">CRM Akademik</h1>
        <p class="login-brand-subtitle">
          Sistem Informasi Layanan Akademik Mahasiswa<br>
          Prodi Sistem Informasi
        </p>

        <div class="login-brand-features">
          <div class="login-feature-item">
            <div class="login-feature-icon">📊</div>
            <span>Dashboard monitoring mahasiswa real-time</span>
          </div>
          <div class="login-feature-item">
            <div class="login-feature-icon">📋</div>
            <span>Pengajuan layanan akademik digital</span>
          </div>
          <div class="login-feature-item">
            <div class="login-feature-icon">⏰</div>
            <span>Pengingat otomatis masa cuti mahasiswa</span>
          </div>
          <div class="login-feature-item">
            <div class="login-feature-icon">💬</div>
            <span>Integrasi notifikasi WhatsApp</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ========== RIGHT: LOGIN FORM ========== -->
    <div class="login-form-side">
      <div class="login-form-container">
        <div class="login-form-header">
          <div class="logo-small">
            <div class="logo-small-icon">🎓</div>
            <span class="logo-small-text">CRM Akademik</span>
          </div>
          <h2 class="login-form-title">Selamat Datang</h2>
          <p class="login-form-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Flash error --}}
        @if(session('error'))
        <div class="login-alert" id="loginAlert">
          <span class="login-alert-icon">⚠️</span>
          <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Demo: hidden alert for JS demo --}}
        <div class="login-alert hidden" id="loginAlertDemo">
          <span class="login-alert-icon">⚠️</span>
          <span>Nama pengguna atau password salah</span>
        </div>

        <form class="login-form" id="loginForm" method="POST" action="/login">
          @csrf

          <!-- Nama Pengguna -->
          <div class="form-group">
            <label class="form-label" for="name">
              Nama Pengguna <span class="required">*</span>
            </label>
            <div class="search-wrapper">
              <span class="search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </span>
              <input
                type="text"
                class="form-input"
                id="name"
                name="name"
                placeholder="Masukkan nama pengguna"
                required
                autocomplete="username"
                value="{{ old('name') }}"
              >
            </div>
            <div class="form-error hidden" id="nameError">
              <span>⚠</span> Nama pengguna wajib diisi
            </div>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label class="form-label" for="password">
              Password <span class="required">*</span>
            </label>
            <div class="input-password-wrapper">
              <input
                type="password"
                class="form-input"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
                minlength="6"
                autocomplete="current-password"
              >
              <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" id="eyeIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="form-error hidden" id="passwordError">
              <span>⚠</span> Password minimal 6 karakter
            </div>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-primary btn-block btn-lg" id="loginBtn" style="margin-top: 8px;">
            <span id="loginBtnText">Masuk</span>
            <span class="spinner hidden" id="loginSpinner"></span>
          </button>
        </form>

        <div class="login-footer">
          CRM Akademik © 2026 — Prodi Sistem Informasi
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form       = document.getElementById('loginForm');
      const nameInput  = document.getElementById('name');
      const passInput  = document.getElementById('password');
      const nameError  = document.getElementById('nameError');
      const passError  = document.getElementById('passwordError');
      const loginBtn   = document.getElementById('loginBtn');
      const btnText    = document.getElementById('loginBtnText');
      const spinner    = document.getElementById('loginSpinner');
      const passToggle = document.getElementById('passwordToggle');
      const alertDemo  = document.getElementById('loginAlertDemo');

      // Password visibility toggle
      passToggle.addEventListener('click', function() {
        const type = passInput.type === 'password' ? 'text' : 'password';
        passInput.type = type;
        // Swap icon
        const icon = document.getElementById('eyeIcon');
        if (type === 'text') {
          icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
          icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
      });

      // Frontend validation
      function validateForm() {
        let valid = true;

        // Name
        if (!nameInput.value.trim()) {
          nameInput.classList.add('error');
          nameError.classList.remove('hidden');
          valid = false;
        } else {
          nameInput.classList.remove('error');
          nameError.classList.add('hidden');
        }

        // Password
        if (!passInput.value || passInput.value.length < 6) {
          passInput.classList.add('error');
          passError.classList.remove('hidden');
          valid = false;
        } else {
          passInput.classList.remove('error');
          passError.classList.add('hidden');
        }

        return valid;
      }

      // Remove error on input
      nameInput.addEventListener('input', function() {
        if (this.value.trim()) {
          this.classList.remove('error');
          nameError.classList.add('hidden');
        }
      });

      passInput.addEventListener('input', function() {
        if (this.value.length >= 6) {
          this.classList.remove('error');
          passError.classList.add('hidden');
        }
      });

      // Form submit
      form.addEventListener('submit', function(e) {
        if (!validateForm()) {
          e.preventDefault();
          // Shake animation
          form.classList.add('shake');
          setTimeout(() => form.classList.remove('shake'), 500);
          return;
        }

        // Show loading state
        loginBtn.disabled = true;
        btnText.textContent = 'Memproses...';
        spinner.classList.remove('hidden');
      });
    });
  </script>
</body>
</html>
