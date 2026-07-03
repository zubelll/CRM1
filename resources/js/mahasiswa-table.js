/**
 * mahasiswa-table.js
 * Search & filter real-time, tab switching, side drawer, modal management
 */

document.addEventListener('DOMContentLoaded', function () {

  // ===== Search & Filter =====
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', filterTable);
  }

  // Debounced filter (optional enhancement)
  let filterTimeout;
  window.filterTable = function () {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(applyFilters, 150);
  };

  function applyFilters() {
    const query    = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const angkatan = document.getElementById('filterAngkatan')?.value || '';
    const semester = document.getElementById('filterSemester')?.value || '';
    const activeTab = document.querySelector('.tab-item.active')?.dataset.status || 'all';

    const rows = document.querySelectorAll('#mahasiswaTable tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
      const text       = row.textContent.toLowerCase();
      const rowStatus  = row.dataset.status;
      const rowAngkatan= row.dataset.angkatan;
      const rowSemester= row.dataset.semester;

      let show = true;

      // Text search
      if (query && !text.includes(query)) show = false;

      // Tab filter
      if (activeTab !== 'all' && rowStatus !== activeTab) show = false;

      // Angkatan filter
      if (angkatan && rowAngkatan !== angkatan) show = false;

      // Semester filter
      if (semester && rowSemester !== semester) show = false;

      row.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });

    // Update table info
    const totalCount = rows.length;
    const tableInfo = document.querySelector('.table-info');
    if (tableInfo) {
      tableInfo.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari <strong>${totalCount}</strong> mahasiswa`;
    }
  }

  // ===== Tab Switching =====
  window.filterByStatus = function (status, btn) {
    // Update active tab
    document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    // Refilter
    applyFilters();
  };

  // ===== Side Drawer =====
  window.openDrawer = function (data) {
    const overlay = document.getElementById('drawerOverlay');
    const drawer = document.getElementById('studentDrawer');

    // Populate data
    document.getElementById('drawerAvatar').textContent = data.nama.charAt(0).toUpperCase();
    document.getElementById('drawerName').textContent = data.nama;
    document.getElementById('drawerNim').textContent = `${data.nim} · Angkatan ${data.angkatan}`;
    document.getElementById('drawerSemester').textContent = data.semester;
    document.getElementById('drawerSks').textContent = data.sks_tempuh;
    document.getElementById('drawerIpk').textContent = data.ipk?.toFixed(2) || '-';
    document.getElementById('drawerAngkatan').textContent = data.angkatan;
    document.getElementById('drawerEmail').textContent = data.email || '-';

    // Format phone
    const phone = data.no_whatsapp || '';
    const formatted = phone.replace(/(\d{2})(\d{3,4})(\d{3,4})(\d{3,4})/, '$1-$2-$3-$4');
    document.getElementById('drawerWa').textContent = formatted || '-';

    // Badge
    const statusLabels = {
      'aktif': 'Aktif', 'cuti': 'Cuti',
      'drop_out': 'Drop Out', 'tanpa_keterangan': 'Tanpa Keterangan'
    };
    const badgeClasses = {
      'aktif': 'badge-aktif', 'cuti': 'badge-cuti',
      'drop_out': 'badge-do', 'tanpa_keterangan': 'badge-tanpa-ket'
    };
    const badge = document.getElementById('drawerBadge');
    badge.textContent = statusLabels[data.status] || data.status;
    badge.className = 'badge ' + (badgeClasses[data.status] || '');

    // Store current data for WA button
    drawer.dataset.phone = data.no_whatsapp || '';
    drawer.dataset.nama = data.nama;

    // Show
    overlay.classList.add('show');
    drawer.classList.add('show');
    document.body.style.overflow = 'hidden';
  };

  window.closeDrawer = function () {
    document.getElementById('drawerOverlay').classList.remove('show');
    document.getElementById('studentDrawer').classList.remove('show');
    document.body.style.overflow = '';
  };

  // ===== WhatsApp =====
  window.openWhatsApp = function () {
    const drawer = document.getElementById('studentDrawer');
    const phone = drawer.dataset.phone;
    const nama = drawer.dataset.nama;

    if (!phone) {
      alert('Nomor WhatsApp tidak tersedia.');
      return;
    }

    const message = `Halo ${nama}, kami dari Prodi Sistem Informasi ingin menghubungi Anda terkait layanan akademik.`;
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
  };

  // ===== Modal Management =====
  window.openModal = function (id) {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeModal = function (id) {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
  };

  // Close modal on overlay click
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });

  // Close on ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.show').forEach(m => {
        m.classList.remove('show');
      });
      closeDrawer();
      document.body.style.overflow = '';
    }
  });

  // ===== Edit Modal =====
  window.openEditModal = function (data) {
    document.getElementById('formModalTitle').textContent = 'Edit Mahasiswa';
    document.getElementById('formNama').value = data.nama || '';
    document.getElementById('formNim').value = data.nim || '';
    document.getElementById('formAngkatan').value = data.angkatan || '';
    document.getElementById('formSemester').value = data.semester || '';
    document.getElementById('formStatus').value = data.status || 'aktif';
    document.getElementById('formWa').value = data.no_whatsapp || '';
    document.getElementById('formEmail').value = data.email || '';
    document.getElementById('formIpk').value = data.ipk || '';

    openModal('modalTambah');
  };

  // ===== Delete =====
  let deleteTargetId = null;

  window.openDeleteModal = function (nama, id) {
    deleteTargetId = id;
    document.getElementById('deleteConfirmText').innerHTML =
      `Anda yakin ingin menghapus data <strong>${nama}</strong>? Tindakan ini tidak dapat dibatalkan.`;
    openModal('modalHapus');
  };

  window.confirmDelete = function () {
    // TODO: POST to delete endpoint
    console.log('Delete ID:', deleteTargetId);
    closeModal('modalHapus');
    // Refresh or remove row
  };

  // ===== Submit Mahasiswa =====
  window.submitMahasiswa = function () {
    const form = document.getElementById('formMahasiswa');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // TODO: POST form data to controller
    const data = {
      nama: document.getElementById('formNama').value,
      nim: document.getElementById('formNim').value,
      angkatan: document.getElementById('formAngkatan').value,
      semester: document.getElementById('formSemester').value,
      status: document.getElementById('formStatus').value,
      no_whatsapp: document.getElementById('formWa').value,
      email: document.getElementById('formEmail').value,
      ipk: document.getElementById('formIpk').value,
    };
    console.log('Submit:', data);
    closeModal('modalTambah');
    // Reset form
    form.reset();
    document.getElementById('formModalTitle').textContent = 'Tambah Mahasiswa';
  };

  // ===== Export Excel =====
  window.exportExcel = function () {
    // TODO: redirect to export route
    alert('Export Excel — fitur akan tersedia setelah backend siap.');
  };

});
