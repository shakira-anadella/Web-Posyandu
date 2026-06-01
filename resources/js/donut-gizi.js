import Chart from "chart.js/auto";

export function renderDonutGizi(canvasId, labels, values) {
  const el = document.getElementById(canvasId);
  if (!el) return;

  // Hindari chart dobel saat HMR / reload
  if (el.__chart) el.__chart.destroy();

  // Warna konsisten untuk 6 kelas (urut harus sama dengan donutLabels)
  const colors = [
    "#EF4444", // Gizi Buruk
    "#F97316", // Gizi Kurang
    "#22C55E", // Normal
    "#FACC15", // Risiko Gizi Lebih
    "#3B82F6", // Gizi Lebih
    "#A855F7", // Obesitas
  ];

  // Kalau cuma 1 kategori yang nilainya > 0, donut sering terlihat "putus"
  const nonZeroCount = (values || []).filter((v) => Number(v) > 0).length;
  const spacing = nonZeroCount <= 1 ? 0 : 2;
  const borderRadius = nonZeroCount <= 1 ? 0 : 999;

  el.__chart = new Chart(el, {
    type: "doughnut",
    data: {
      labels,
      datasets: [
        {
          data: values,
          backgroundColor: colors.slice(0, values.length),

          borderColor: "#ffffff",
          borderWidth: 4,
          spacing,
          hoverOffset: 6,

          cutout: "74%",
          borderRadius,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,

      animation: { duration: 700 },

      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => `${ctx.label ?? ""}: ${ctx.parsed ?? 0}`,
          },
        },
      },
    },
  });
}
