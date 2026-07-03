/**
 * dashboard-chart.js
 * Inisialisasi Chart.js: Line chart (tren) & Doughnut chart (proporsi status)
 */

document.addEventListener('DOMContentLoaded', function () {

  // ===== Color Tokens =====
  const colors = {
    aktif:   { bg: 'rgba(16, 185, 129, 0.1)',  border: '#10B981' },
    cuti:    { bg: 'rgba(245, 158, 11, 0.1)',   border: '#F59E0B' },
    do:      { bg: 'rgba(239, 68, 68, 0.1)',    border: '#EF4444' },
    tanpaKet:{ bg: 'rgba(100, 116, 139, 0.1)',  border: '#64748B' },
  };

  // ===== LINE CHART — Tren Mahasiswa per Semester =====
  const trendCtx = document.getElementById('trendChart');
  if (trendCtx) {
    new Chart(trendCtx.getContext('2d'), {
      type: 'line',
      data: {
        labels: ['Ganjil 2022', 'Genap 2022', 'Ganjil 2023', 'Genap 2023', 'Ganjil 2024', 'Genap 2024'],
        datasets: [
          {
            label: 'Aktif',
            data: [1180, 1195, 1210, 1225, 1238, 1247],
            borderColor: colors.aktif.border,
            backgroundColor: colors.aktif.bg,
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointBackgroundColor: colors.aktif.border,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
          },
          {
            label: 'Cuti',
            data: [25, 30, 28, 35, 36, 38],
            borderColor: colors.cuti.border,
            backgroundColor: colors.cuti.bg,
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointBackgroundColor: colors.cuti.border,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
          },
          {
            label: 'Drop Out',
            data: [8, 10, 9, 11, 12, 12],
            borderColor: colors.do.border,
            backgroundColor: colors.do.bg,
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointBackgroundColor: colors.do.border,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
          },
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 20,
              font: {
                family: "'Inter', sans-serif",
                size: 12,
                weight: '500',
              }
            }
          },
          tooltip: {
            backgroundColor: '#1E293B',
            titleFont: { family: "'Inter', sans-serif", size: 13, weight: '600' },
            bodyFont:  { family: "'Inter', sans-serif", size: 12 },
            padding: 12,
            cornerRadius: 8,
            displayColors: true,
            usePointStyle: true,
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              font: { family: "'Inter', sans-serif", size: 11 },
              color: '#64748B',
            },
            border: { display: false }
          },
          y: {
            beginAtZero: false,
            grid: {
              color: 'rgba(0,0,0,0.04)',
              drawBorder: false,
            },
            ticks: {
              font: { family: "'Inter', sans-serif", size: 11 },
              color: '#64748B',
              padding: 8,
            },
            border: { display: false }
          }
        }
      }
    });
  }

  // ===== DOUGHNUT CHART — Proporsi Status =====
  const statusCtx = document.getElementById('statusChart');
  if (statusCtx) {
    new Chart(statusCtx.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: ['Aktif', 'Cuti', 'Drop Out', 'Tanpa Keterangan'],
        datasets: [{
          data: [1247, 38, 12, 23],
          backgroundColor: [
            colors.aktif.border,
            colors.cuti.border,
            colors.do.border,
            colors.tanpaKet.border,
          ],
          borderWidth: 0,
          hoverOffset: 8,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 16,
              font: {
                family: "'Inter', sans-serif",
                size: 12,
                weight: '500',
              }
            }
          },
          tooltip: {
            backgroundColor: '#1E293B',
            titleFont: { family: "'Inter', sans-serif", size: 13, weight: '600' },
            bodyFont:  { family: "'Inter', sans-serif", size: 12 },
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const value = context.parsed;
                const pct = ((value / total) * 100).toFixed(1);
                return ` ${context.label}: ${value.toLocaleString()} (${pct}%)`;
              }
            }
          }
        }
      }
    });
  }

});
