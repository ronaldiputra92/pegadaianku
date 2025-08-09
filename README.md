# Sistem Informasi Pegadaian (Pegadaianku)

Sistem Informasi Pegadaian yang komprehensif untuk mengelola transaksi gadai, pembayaran, nasabah, dan laporan keuangan dengan fitur notifikasi jatuh tempo otomatis.

## 🚀 Fitur Utama

### 👥 Manajemen Pengguna (3 Role)
- **Admin**: Akses penuh ke semua fitur sistem
- **Petugas**: Mengelola transaksi, pembayaran, dan nasabah
- **Nasabah**: Melihat transaksi dan riwayat pembayaran

### 💰 Transaksi Gadai
- Pencatatan transaksi gadai dengan detail barang
- Perhitungan bunga otomatis
- Status transaksi (Aktif, Diperpanjang, Lunas, Jatuh Tempo, Lelang)
- Perpanjangan jangka waktu
- Kode transaksi otomatis

### 💳 Manajemen Pembayaran
- Pembayaran bunga, sebagian, atau pelunasan
- Kalkulator pembayaran otomatis
- Struk pembayaran
- Riwayat pembayaran lengkap

### 🔔 Sistem Notifikasi
- Notifikasi jatuh tempo otomatis
- Peringatan transaksi overdue
- Notifikasi pembayaran
- Dashboard notifikasi real-time

### 📊 Laporan Keuangan
- Laporan transaksi harian/bulanan
- Laporan pembayaran dan pendapatan
- Grafik statistik interaktif
- Export laporan

### 👤 Manajemen Nasabah
- Database nasabah lengkap
- Riwayat transaksi per nasabah
- Profil dan kontak nasabah

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js
- **Icons**: Font Awesome
- **JavaScript**: Alpine.js

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0+
- Web Server (Apache/Nginx)

## 🚀 Instalasi

### Metode 1: Setup Otomatis (Recommended)
```bash
# Jalankan script setup otomatis
setup.bat
```

### Metode 2: Manual Setup

#### 1. Install Dependencies
```bash
composer install
```

#### 2. Konfigurasi Environment
File `.env` sudah dikonfigurasi. Jika perlu, sesuaikan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pegadaianku
DB_USERNAME=root
DB_PASSWORD=
```

#### 3. Generate Application Key
```bash
php artisan key:generate
```

#### 4. Setup Database
```bash
# Jalankan migrasi dan seeder
migrate.bat
```

#### 5. Jalankan Server
```bash
# Start development server
run.bat
```

### 🔧 Troubleshooting

Jika mengalami error:

#### Error: "routes/auth.php not found"
```bash
# Jalankan fix script
fix.bat
```

#### Error: Database connection
```bash
# 1. Pastikan MySQL berjalan
# 2. Cek konfigurasi .env
# 3. Jalankan migrasi ulang
migrate.bat
```

#### Error: Cache issues
```bash
# Clear semua cache
fix.bat
```

#### Cek status aplikasi
```bash
# Cek semua komponen
check.bat
```

## 👤 Akun Demo

Setelah seeding, Anda dapat menggunakan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@pegadaianku.com | password |
| Petugas | petugas@pegadaianku.com | password |
| Nasabah | nasabah@pegadaianku.com | password |

## 📱 Struktur Aplikasi

### Models
- `User` - Manajemen pengguna dengan role system
- `PawnTransaction` - Transaksi gadai
- `Payment` - Pembayaran
- `Notification` - Sistem notifikasi

### Controllers
- `DashboardController` - Dashboard untuk setiap role
- `PawnTransactionController` - CRUD transaksi gadai
- `PaymentController` - Manajemen pembayaran
- `UserController` - Manajemen pengguna (Admin only)
- `CustomerController` - Manajemen nasabah
- `ReportController` - Laporan dan statistik

### Middleware
- `RoleMiddleware` - Kontrol akses berdasarkan role

## 🎨 Fitur UI/UX

- **Responsive Design**: Optimized untuk desktop dan mobile
- **Dark/Light Theme**: Interface yang modern dengan Tailwind CSS
- **Interactive Charts**: Grafik statistik dengan Chart.js
- **Real-time Notifications**: Notifikasi real-time di header
- **Form Validation**: Validasi form yang komprehensif
- **Loading States**: Feedback visual untuk user actions

## 🔐 Keamanan

- Authentication dengan Laravel Breeze
- Role-based access control
- CSRF protection
- Input validation dan sanitization
- Password hashing dengan bcrypt

## 📊 Fitur Bisnis

### Perhitungan Bunga
- Bunga dihitung per bulan
- Default rate: 1.25% per bulan
- Perhitungan otomatis total bunga dan jumlah yang harus dibayar

### Status Transaksi
- **Aktif**: Transaksi berjalan normal
- **Diperpanjang**: Jangka waktu diperpanjang
- **Lunas**: Sudah dibayar penuh
- **Jatuh Tempo**: Melewati batas waktu
- **Lelang**: Akan dilelang

### Jenis Pembayaran
- **Bunga**: Pembayaran bunga saja
- **Sebagian**: Pembayaran sebagian (bunga + pokok)
- **Pelunasan**: Pembayaran penuh

## 🔄 Workflow Sistem

1. **Nasabah datang** dengan barang yang akan digadaikan
2. **Petugas** melakukan penilaian dan membuat transaksi
3. **Sistem** menghitung bunga dan tanggal jatuh tempo otomatis
4. **Notifikasi** dikirim untuk peringatan jatuh tempo
5. **Pembayaran** dapat dilakukan kapan saja
6. **Laporan** tersedia untuk analisis bisnis

## 🚀 Pengembangan Lanjutan

Fitur yang dapat dikembangkan:
- [ ] SMS/Email notifications
- [ ] Barcode/QR code untuk transaksi
- [ ] Mobile app dengan API
- [ ] Integration dengan payment gateway
- [ ] Sistem lelang online
- [ ] Multi-branch support
- [ ] Advanced reporting dengan export PDF/Excel

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Email: support@pegadaianku.com
- GitHub Issues: [Create an issue](https://github.com/username/pegadaianku/issues)

---

**Pegadaianku** - Sistem Informasi Pegadaian Modern dengan Laravel & Tailwind CSS