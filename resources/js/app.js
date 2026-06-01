import './bootstrap';
import { renderDonutGizi } from "./donut-gizi";
import { renderRiwayatChart } from "./riwayat-chart";

document.addEventListener("DOMContentLoaded", () => {
  const el = document.getElementById("donutGizi");
  if (!el) return;

  const labels = JSON.parse(el.dataset.labels || "[]");
  const values = JSON.parse(el.dataset.values || "[]");

  renderDonutGizi("donutGizi", labels, values);
});

document.addEventListener("DOMContentLoaded", () => {
  renderRiwayatChart("riwayatChart");
});

