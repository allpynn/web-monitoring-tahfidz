# Monitoring Tahfidz Al-Qur'an - Ponpes Al Mujahidin Balikpapan

Sistem Monitoring Tahfidz Al-Qur'an berbasis web yang dirancang khusus untuk internal **Pondok Pesantren Al Mujahidin Balikpapan**. Sistem ini memudahkan kolaborasi antara Asatidz (Guru) dan Wali Santri dalam memantau perkembangan hafalan santri secara real-time.

## ✨ Fitur Utama
- **Autentikasi Dual-Mode**: Login menggunakan Email atau Nomor WhatsApp.
- **Role-Based Access Control (RBAC)**: Admin, Guru, dan Orang Tua.
- **Guru Dashboard**: Input hafalan, absensi santri, dan rekapitulasi harian.
- **Parent Dashboard**: Pantau progres hafalan anak secara personal dengan bar progres visual.
- **Pencarian Instan**: Fitur filter tabel cerdas menggunakan Alpine.js.
- **Premium UI**: Desain modern dengan dukungan **Dark Mode** penuh dan tema warna khas Mujahidin (Emerald).

## 🚀 Teknologi
- **Framework**: Laravel 11.x
- **Frontend**: Tailwind CSS & Alpine.js
- **Database**: SQLite (Local Dev)
- **UI Components**: Blade Components (Custom)

## 🛠️ Instalasi
1. Clone repositori:
   ```bash
   git clone https://github.com/allpynn/web-monitoring-tahfidz.git;
   ```
2. Instal dependensi:
   ```bash
   composer install
   npm install
   ```
3. Konfigurasi .env:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Jalankan Migrasi & Seed:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Jalankan Aplikasi:
   ```bash
   php artisan serve
   npm run dev
   ```

## 👤 Akun Demo
- **Admin**: `admin@tahfidz.com` / `password`
- **Guru**: `ahmad@tahfidz.com` / `password`
- **Wali Santri**: `budi@gmail.com` / `password`

---
*Dibuat dengan ❤️ untuk kemajuan pendidikan Islam di Balikpapan.*
