/**
 * wa-generator.js
 * Real-time template preview (Auto Play) & WhatsApp link generator
 */

document.addEventListener('DOMContentLoaded', function () {

  // ===== Auto Play — Real-time Preview =====
  const templateInput = document.getElementById('kontakTemplate');

  if (templateInput) {
    templateInput.addEventListener('input', updateWAPreview);
    // Initial preview
    updateWAPreview();
  }

  // Update preview time
  updatePreviewTime();
});

/**
 * Update the WhatsApp bubble preview in real-time
 * Replaces template variables with sample data
 */
function updateWAPreview() {
  const templateInput = document.getElementById('kontakTemplate');
  const previewBubble = document.getElementById('waBubblePreview');
  const contactName = document.getElementById('kontakNama');
  const previewContactName = document.getElementById('previewContactName');

  if (!templateInput || !previewBubble) return;

  const template = templateInput.value || '';

  // Replace variables with sample data
  const previewText = template
    .replace(/\{nama_mahasiswa\}/g, 'Contoh Mahasiswa')
    .replace(/\{nim\}/g, '2021001001')
    .replace(/\{keperluan\}/g, 'Surat Keterangan Aktif')
    .replace(/\{sisa_hari\}/g, '7');

  previewBubble.textContent = previewText || '(Template pesan kosong)';

  // Update contact name in preview header
  if (contactName && previewContactName) {
    previewContactName.textContent = contactName.value || 'Nama Kontak';
  }
}

/**
 * Update the timestamp in preview
 */
function updatePreviewTime() {
  const timeEl = document.getElementById('previewTime');
  if (timeEl) {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const mins = now.getMinutes().toString().padStart(2, '0');
    timeEl.textContent = `${hours}:${mins}`;
  }
}

/**
 * Generate a WhatsApp deep link URL
 * @param {string} phone - International phone number (e.g., 6281234567890)
 * @param {string} template - Message template with placeholders
 * @param {Object} studentData - Student data object
 * @returns {string} wa.me URL
 */
function generateWALink(phone, template, studentData) {
  const message = template
    .replace(/\{nama_mahasiswa\}/g, studentData.nama || studentData.name || '')
    .replace(/\{nim\}/g, studentData.nim || '')
    .replace(/\{keperluan\}/g, studentData.keperluan || studentData.need || '')
    .replace(/\{sisa_hari\}/g, studentData.sisa_hari || studentData.sisaHari || '');

  return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
}

/**
 * Open WhatsApp with generated link
 * @param {string} phone - Phone number
 * @param {string} template - Message template
 * @param {Object} data - Data for template replacement
 */
function openWhatsAppLink(phone, template, data) {
  const url = generateWALink(phone, template, data);
  window.open(url, '_blank');
}

// Make functions globally available
window.updateWAPreview = updateWAPreview;
window.generateWALink = generateWALink;
window.openWhatsAppLink = openWhatsAppLink;
