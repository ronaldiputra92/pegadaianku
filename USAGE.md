# Panduan Penggunaan Sistem Pegadaian

## 🚀 Quick Start

### 1. Setup Database
```bash
# Jalankan migrasi dan seeder
migrate.bat
```

### 2. Jalankan Server
```bash
# Start development server
run.bat
```

### 3. Akses Aplikasi
Buka browser dan kunjungi: `http://localhost:8000`

## 👤 Login Akun Demo

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Admin** | admin@pegadaianku.com | password | Semua fitur |
| **Petugas** | petugas@pegadaianku.com | password | Transaksi, Pembayaran, Nasabah |
| **Nasabah** | nasabah@pegadaianku.com | password | Lihat transaksi sendiri |

## 📋 Workflow Penggunaan

### Untuk Admin/Petugas:

#### 1. Membuat Transaksi Gadai Baru
1. Login sebagai Admin/Petugas
2. Klik menu **"Transaksi"** → **"Transaksi Baru"**
3. Pilih nasabah dari dropdown
4. Isi detail barang yang digadaikan
5. Tentukan jumlah pinjaman dan suku bunga
6. Sistem akan menghitung bunga dan total otomatis
7. Klik **"Simpan Transaksi"**

#### 2. Memproses Pembayaran
1. Dari daftar transaksi, klik ikon **💰** (pembayaran)
2. Atau klik menu **"Pembayaran"** → **"Pembayaran Baru"**
3. Pilih transaksi yang akan dibayar
4. Pilih jenis pembayaran:
   - **Bunga**: Hanya bayar bunga
   - **Sebagian**: Bayar sebagian (bunga + pokok)
   - **Pelunasan**: Bayar semua
5. Masukkan jumlah pembayaran
6. Klik **"Proses Pembayaran"**

#### 3. Mengelola Nasabah
1. Klik menu **"Nasabah"**
2. Untuk menambah nasabah baru: **"Tambah Nasabah"**
3. Isi data lengkap nasabah
4. Klik **"Simpan"**

#### 4. Melihat Laporan
1. Klik menu **"Laporan"**
2. Pilih jenis laporan:
   - Laporan Transaksi
   - Laporan Pembayaran
   - Laporan Keuangan
3. Filter berdasarkan tanggal
4. Export ke PDF/Excel jika diperlukan

### Untuk Nasabah:

#### 1. Melihat Transaksi
1. Login sebagai nasabah
2. Dashboard menampilkan ringkasan transaksi aktif
3. Lihat detail bunga yang harus dibayar
4. Cek tanggal jatuh tempo

#### 2. Melihat Riwayat Pembayaran
1. Dari dashboard, klik **"Riwayat Pembayaran"**
2. Lihat semua pembayaran yang telah dilakukan

## 🔔 Sistem Notifikasi

### Notifikasi Otomatis:
- **7 hari sebelum jatuh tempo**: Peringatan akan jatuh tempo
- **Hari jatuh tempo**: Notifikasi jatuh tempo
- **Setelah jatuh tempo**: Notifikasi overdue
- **Pembayaran berhasil**: Konfirmasi pembayaran

### Cara Melihat Notifikasi:
1. Klik ikon 🔔 di header
2. Notifikasi baru ditandai dengan badge merah
3. Klik notifikasi untuk membaca detail

## 💰 Perhitungan Bunga

### Formula:
- **Bunga per bulan** = Pokok Pinjaman × (Suku Bunga / 100)
- **Total bunga** = Bunga per bulan × Jangka waktu (bulan)
- **Total yang harus dibayar** = Pokok + Total bunga

### Contoh:
- Pinjaman: Rp 1.000.000
- Suku bunga: 1.25% per bulan
- Jangka waktu: 4 bulan

Perhitungan:
- Bunga per bulan: Rp 1.000.000 × 1.25% = Rp 12.500
- Total bunga: Rp 12.500 × 4 = Rp 50.000
- Total bayar: Rp 1.000.000 + Rp 50.000 = Rp 1.050.000

## 📊 Status Transaksi

| Status | Deskripsi | Aksi yang Bisa Dilakukan |
|--------|-----------|--------------------------|
| **Aktif** | Transaksi berjalan normal | Bayar, Edit, Perpanjang |
| **Diperpanjang** | Jangka waktu diperpanjang | Bayar, Edit |
| **Lunas** | Sudah dibayar penuh | Lihat saja |
| **Jatuh Tempo** | Melewati batas waktu | Bayar, Perpanjang |
| **Lelang** | Akan dilelang | Lihat saja |

## 🔧 Troubleshooting

### Database Error:
```bash
# Reset database
migrate.bat
```

### Server Error:
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Permission Error:
```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

## 📞 Bantuan

Jika mengalami masalah:
1. Periksa log error di `storage/logs/laravel.log`
2. Pastikan database MySQL berjalan
3. Pastikan PHP versi 8.2+
4. Cek konfigurasi `.env`

## 🎯 Tips Penggunaan

1. **Backup Database**: Selalu backup database sebelum update
2. **Monitor Jatuh Tempo**: Cek dashboard secara rutin untuk transaksi yang akan jatuh tempo
3. **Update Data Nasabah**: Pastikan data kontak nasabah selalu update
4. **Gunakan Filter**: Manfaatkan filter di halaman transaksi dan pembayaran
5. **Export Laporan**: Export laporan secara berkala untuk arsip

---

**Selamat menggunakan Sistem Pegadaian! 🎉**