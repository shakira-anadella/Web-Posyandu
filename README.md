# Smart-Toddler Health Monitoring System

Platform aplikasi web terintegrasi berbasis *Machine Learning* untuk pemantauan, analisis, dan klasifikasi status gizi serta tumbuh kembang balita secara akurat sesuai standar Permenkes. Proyek ini dikembangkan sebagai bagian dari penyelesaian studi strata satu (Skripsi) Teknik Informatika.

---

## 🚀 Fitur Utama
* **Dashboard Administrasi (Laravel):** Antarmuka manajemen data balita, pencatatan hasil pengukuran, riwayat tumbuh kembang, serta visualisasi laporan kesehatan berkala.
* **Layanan Mikro Prediksi (FastAPI):** Menyediakan titik akhir (*endpoint*) API berkecepatan tinggi untuk pemrosesan klasifikasi status gizi secara *real-time*.
* **Model Machine Learning (XGBoost + Optuna):** Model klasifikasi gizi yang dioptimalkan menggunakan teknik *Bayesian Optimization* (Optuna) dan penyeimbangan data (*SMOTE*) untuk akurasi prediksi tinggi.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)
* **Backend Web Framework:** Laravel (PHP)
* **Machine Learning & Microservice:** Python, FastAPI, XGBoost, Optuna, Scikit-Learn, SMOTE (Team Data Science Process methodology)
* **Database & Frontend:** MySQL, HTML, Tailwind CSS, JavaScript
* **Tools & Deployment:** VS Code, Git, GitHub, Vercel / Cloud Server

---

## 📂 Struktur Repositori
```text
├── backend-laravel/    # Web dashboard utama & manajemen database
├── ml-fastapi/         # Microservice model Machine Learning (XGBoost & Optuna)
├── dataset/            # Data latih & skrip pengolahan data
└── README.md           # Dokumentasi proyek
