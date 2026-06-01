import Chart from "chart.js/auto";

export function renderRiwayatChart(canvasId = "riwayatChart") {
  const el = document.getElementById(canvasId);
  if (!el) return;

  if (el.__chart) el.__chart.destroy();

  const points = JSON.parse(el.dataset.points || "[]");

  const dataBB = points.map((p) => ({
    x: Number(p.umur),
    y: Number(p.bb),
    tgl: p.tgl,
  }));

  const dataTB = points.map((p) => ({
    x: Number(p.umur),
    y: Number(p.tb),
    tgl: p.tgl,
  }));

  el.__chart = new Chart(el, {
    type: "line",
    data: {
      datasets: [
        {
          label: "Berat Badan (kg)",
          data: dataBB,
          parsing: false,
          tension: 0.35,
          borderWidth: 2,
          pointRadius: 3,
          pointHoverRadius: 6,
        },
        {
          label: "Tinggi Badan (cm)",
          data: dataTB,
          parsing: false,
          tension: 0.35,
          borderWidth: 2,
          pointRadius: 3,
          pointHoverRadius: 6,
          yAxisID: "y2",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "top",
        },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              return `${ctx.dataset.label}: ${ctx.parsed.y} (umur ${ctx.parsed.x} bln)`;
            },
          },
        },
      },
      scales: {
        x: {
          type: "linear",
          title: {
            display: true,
            text: "Umur (bulan)",
          },
          min: 0,
          max: 60,
          ticks: {
            stepSize: 6,
          },
        },
        y: {
          title: {
            display: true,
            text: "Berat (kg)",
          },
        },
        y2: {
          position: "right",
          grid: {
            drawOnChartArea: false,
          },
          title: {
            display: true,
            text: "Tinggi (cm)",
          },
        },
      },
    },
  });
}