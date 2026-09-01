<div align="center">

# LumiMate

**Smart Skincare Routine Planner & Tracker**

Sistem pakar (Expert System) berbasis Forward Chaining yang mengubah kebingungan skincare jadi rutinitas yang aman, personal, dan konsisten.

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

</div>

---

## ✨ Kenapa LumiMate?

Banyak orang salah urutan pakai produk, campur bahan aktif yang saling berkonflik, atau ganti-ganti rutinitas tanpa arah. LumiMate hadir sebagai "dermatolog mini" yang mengonsultasi kondisi kulitmu, lalu menyusun rutinitas skincare yang aman berdasarkan referensi ilmiah — bukan sekadar tracker biasa.

## 🚀 Fitur Utama

- 🧴 **Konsultasi Kulit** — kenali jenis kulit, masalah utama, dan sensitivitas lewat kuesioner terstruktur (diadaptasi dari Baumann Skin Type Indicator)
- ⚠️ **Ingredient Conflict Checker** — deteksi otomatis kombinasi bahan aktif yang berisiko (mis. Retinol + AHA/BHA), lengkap dengan solusinya
- 📅 **Routine Generator** — susun urutan layering & jadwal pagi/malam otomatis, termasuk pola *skin cycling*
- ✅ **Daily Tracker & Streak** — pantau konsistensi rutinitas harian
- 📊 **Progress Dashboard** — visualisasi hidrasi & konsistensi kulit dengan Chart.js
- 🛡️ **Safety Guardrails** — peringatan otomatis untuk kondisi kehamilan, sunscreen yang terlewat, atau rutinitas yang terlalu agresif untuk pemula

## 🧠 Di Baliknya

LumiMate menjalankan mesin inferensi **Forward Chaining** dengan **Certainty Factor**, mencocokkan fakta kondisi kulitmu dengan rule base yang seluruhnya bersumber dari jurnal dermatologi (AAD, PubMed, Cleveland Clinic, dan lainnya) — jadi tiap rekomendasi bisa dipertanggungjawabkan.

> 📄 Detail metodologi, matriks konflik ingredient, struktur database, dan referensi ilmiah lengkap ada di [`docs/METHODOLOGY.md`](docs/METHODOLOGY.md).

## 🛠️ Tech Stack

Laravel · PHP · MySQL · Blade · Tailwind CSS · Chart.js

## ⚡ Quick Start

```bash
git clone https://github.com/username/lumimate.git
cd lumimate
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## 🗺️ Roadmap

- [x] Konsultasi kulit, conflict checker, routine generator, daily tracker (MVP)
- [ ] Notifikasi reminder & grafik konsistensi bulanan
- [ ] AI skin analysis dari foto & chatbot konsultasi

---

<div align="center">

*"Ritual Adalah Segalanya."*

</div>