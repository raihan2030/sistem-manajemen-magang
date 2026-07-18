# Sistem Manajemen Magang Berbasis Website (SKPD Pemerintah Kota Banjarmasin)

Proyek ini dikembangkan menggunakan framework **Laravel 13** dengan **PHP 8.4** dan diisolasi sepenuhnya di dalam lingkungan **Docker (Laravel Sail)** untuk menjaga konsistensi *environment* antar pengembang.

---

## 🛠️ Prasyarat (Prerequisites)

Sebelum memulai, pastikan mesin lokal Anda sudah terpasang:
1. **Docker Desktop** (Sudah berjalan).
2. **WSL 2 (Windows Subsystem for Linux)** dengan distro Ubuntu (Khusus pengguna Windows).
3. **Git** untuk *cloning* repositori.

*Catatan: Anda tidak memerlukan PHP atau MySQL lokal di laptop Anda. Semua sudah disediakan oleh Docker.*

---

## Instalasi WSL
### 1. Instal WSL (jika belum ada)
```bash
wsl --install
```
### 2. Buka WSL
```bash
wsl
```
### 3. Masuk ke root
```bash
cd ~
```
### 4. Instal Composer di WSL
```bash
sudo apt install composer
```

## 🚀 Langkah Instalasi (Quick Start)

Ikuti langkah-langkah di bawah ini secara berurutan di dalam terminal WSL/Linux Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/raihan2030/sistem-manajemen-magang
cd sistem-manajemen-magang
code .
```

### 2. Setup File `.env`
Buat file baru bernama `.env` di root proyek, lalu salin dan tempel seluruh konfigurasi yang diberikan developer.

### 3. Install Dependensi Composer via Docker
Jalankan temporary container berikut untuk mengunduh paket `vendor` dan Laravel Sail pertama kali:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Membuat Alias Laravel Sail
#### a. Agar tidak perlu mengetik rute `./vendor/bin/sail` yang panjang, buatlah alias di terminal Anda:

```bash
alias sail="./vendor/bin/sail"
```

#### b. Atau langsung tulis di `.bashrc`

```bash
nano ~/.bashrc
```
Setelah itu, paste `alias sail="./vendor/bin/sail"` di paling bawah, lalu Save.

### 5. Nyalakan Container Docker
Bangun dan jalankan semua ekosistem container di latar belakang:

```bash
sail up -d
```

### 6. Generate Application Key

```bash
sail artisan key:generate
```

### 7. Jalankan Migrasi Database

```bash
sail artisan migrate
```
