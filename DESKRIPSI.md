# Deskripsi Proyek: Livewire Stisla

## Ringkasan

Livewire Stisla adalah sebuah starter template aplikasi web modern yang dibangun menggunakan framework **Laravel 11** dan **Livewire 3**, dipadukan dengan desain dashboard admin yang elegan dari **Stisla**. Proyek ini dirancang untuk mempercepat pengembangan aplikasi dengan menyediakan fitur-fitur esensial seperti autentikasi pengguna yang kuat dan pengelolaan layout yang responsif.

Tujuan utama proyek ini adalah memberikan fondasi yang kokoh bagi pengembang yang ingin membangun aplikasi admin panel interaktif tanpa harus memulai dari nol, memanfaatkan reaktivitas Livewire untuk pengalaman pengguna yang mulus tanpa banyak menulis JavaScript kustom.

## Fitur Utama

### 1. Autentikasi & Otorisasi Lanjutan

-   **Multi-Role Support**: Menggunakan **Laratrust** untuk pengelolaan peran (Role) dan izin (Permission). Secara default mendukung peran `admin` dan `user`.
-   **Manajemen Akun**: Fitur untuk mengaktifkan atau menonaktifkan akun pengguna (Active/Inactive), memberikan kontrol penuh kepada admin atas akses pengguna.

### 2. Antarmuka Modern & Responsif

-   **Template Stisla**: Mengadopsi desain Stisla yang bersih, modern, dan responsif, cocok untuk berbagai perangkat.
-   **Komponen Livewire**: Penggunaan komponen Livewire untuk elemen UI yang dinamis dan interaktif seperti form validasi real-time.

### 3. Dasar Teknologi Terkini

-   Dibangun di atas **Laravel 11**, menjamin performa, keamanan, dan fitur developer terbaru.
-   Integrasi **TailwindCSS** (melalui Vite) untuk kustomisasi styling yang fleksibel.

## Teknologi yang Digunakan

-   **Backend**: PHP 8.2+, Laravel 11
-   **Full-Stack Framework**: Livewire 3
-   **Frontend Template**: Stisla Admin Template
-   **Styling**: Bootstrap (Bawaan Stisla) & TailwindCSS (Optional/Support)
-   **Database**: MySQL
-   **Role Management**: Laratrust
-   **Build Tool**: Vite

## Struktur Proyek

Proyek ini mengikuti struktur standar Laravel dengan beberapa penyesuaian untuk Livewire:

-   `app/Livewire`: Berisi komponen-komponen logika tampilan.
-   `resources/views/livewire`: Berisi template blade untuk komponen Livewire.
-   `routes/web.php`: Definisi rute yang mencakup middleware untuk role admin dan user.
