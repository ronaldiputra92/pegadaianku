# Fitur Perpanjangan Gadai

## Overview
Fitur Perpanjangan Gadai memungkinkan nasabah untuk memperpanjang jatuh tempo transaksi gadai mereka dengan membayar biaya perpanjangan yang terdiri dari bunga, denda (jika terlambat), dan biaya administrasi.

## Fitur Utama

### 1. **Perhitungan Biaya Otomatis**
- **Bunga Perpanjangan**: Dihitung berdasarkan suku bunga transaksi × jumlah pinjaman × periode perpanjangan
- **Denda Keterlambatan**: Dihitung jika transaksi sudah melewati jatuh tempo (0.1% per hari)
- **Biaya Administrasi**: Biaya tetap untuk setiap perpanjangan (default: Rp 50,000)

### 2. **Proses Perpanjangan**
- Cari transaksi berdasarkan kode transaksi
- Pilih periode perpanjangan (1-6 bulan)
- Sistem menghitung biaya secara otomatis
- Konfirmasi dan proses perpanjangan
- Generate bukti perpanjangan

### 3. **Cetak Bukti Perpanjangan**
- Bukti perpanjangan dalam format PDF
- Berisi detail transaksi, biaya, dan jatuh tempo baru
- Nomor bukti unik untuk setiap perpanjangan

### 4. **Riwayat Perpanjangan**
- Tracking semua perpanjangan per transaksi
- Detail biaya dan tanggal setiap perpanjangan
- Integrasi dengan halaman detail transaksi

## Struktur Database

### Tabel `pawn_extensions`
```sql
- id (Primary Key)
- transaction_id (Foreign Key ke pawn_transactions)
- officer_id (Foreign Key ke users)
- extension_code (Kode unik perpanjangan)
- original_due_date (Jatuh tempo lama)
- new_due_date (Jatuh tempo baru)
- extension_months (Periode perpanjangan dalam bulan)
- interest_amount (Jumlah bunga perpanjangan)
- penalty_amount (Jumlah denda keterlambatan)
- admin_fee (Biaya administrasi)
- total_amount (Total biaya perpanjangan)
- notes (Catatan perpanjangan)
- receipt_number (Nomor bukti perpanjangan)
- receipt_printed (Status cetak bukti)
- receipt_printed_at (Waktu cetak bukti)
- created_at, updated_at
```

## File-file Utama

### Models
- `app/Models/PawnExtension.php` - Model untuk perpanjangan gadai
- `app/Models/PawnTransaction.php` - Updated dengan relationship extensions

### Controllers
- `app/Http/Controllers/PawnExtensionController.php` - Controller utama perpanjangan
- `app/Http/Controllers/PawnTransactionController.php` - Updated method extend()

### Views
- `resources/views/extensions/index.blade.php` - Daftar perpanjangan
- `resources/views/extensions/create.blade.php` - Form perpanjangan
- `resources/views/extensions/show.blade.php` - Detail perpanjangan
- `resources/views/extensions/receipt.blade.php` - Bukti perpanjangan PDF

### Migration
- `database/migrations/2025_01_15_000003_create_pawn_extensions_table.php`

### Configuration
- `config/pawn.php` - Konfigurasi sistem pegadaian

## Routes

```php
// Perpanjangan Gadai
Route::resource('extensions', PawnExtensionController::class)->except(['edit', 'update', 'destroy']);
Route::get('extensions/{extension}/receipt', [PawnExtensionController::class, 'printReceipt'])->name('extensions.receipt');
Route::post('extensions/calculate-fees', [PawnExtensionController::class, 'calculateFees'])->name('extensions.calculate-fees');
Route::get('extensions/transaction-details', [PawnExtensionController::class, 'getTransactionDetails'])->name('extensions.transaction-details');

// Update route transaksi
Route::post('transactions/{transaction}/extend', [PawnTransactionController::class, 'extend'])->name('transactions.extend');
```

## Konfigurasi

File `config/pawn.php` berisi pengaturan:

```php
'extension_admin_fee' => 50000, // Biaya admin perpanjangan
'max_extension_months' => 6, // Maksimal periode perpanjangan
'penalty_rate_per_day' => 0.001, // Rate denda per hari (0.1%)
```

## Cara Penggunaan

### 1. **Akses Menu Perpanjangan**
- Login sebagai Admin atau Petugas
- Klik menu "Perpanjangan Gadai" di sidebar
- Atau dari detail transaksi, klik tombol "Perpanjang"

### 2. **Proses Perpanjangan**
1. Masukkan kode transaksi di form pencarian
2. Sistem akan menampilkan detail transaksi
3. Pilih periode perpanjangan (1-6 bulan)
4. Sistem menghitung biaya otomatis
5. Tambahkan catatan jika diperlukan
6. Klik "Proses Perpanjangan"

### 3. **Cetak Bukti**
- Setelah perpanjangan berhasil, klik "Cetak Bukti"
- Bukti akan di-generate dalam format PDF
- Bukti dapat dicetak ulang dari daftar perpanjangan

## Status Transaksi

Setelah perpanjangan:
- Status transaksi berubah menjadi "extended"
- Jatuh tempo diperbarui sesuai periode perpanjangan
- Periode pinjaman bertambah sesuai perpanjangan

## Validasi

### Transaksi yang Dapat Diperpanjang
- Status: active, extended, atau overdue
- Belum lunas
- Masih dalam sistem

### Batasan Perpanjangan
- Maksimal 6 bulan per perpanjangan
- Tidak ada batasan jumlah perpanjangan
- Biaya harus dibayar untuk setiap perpanjangan

## Integrasi

### Dashboard
- Menampilkan transaksi yang diperpanjang
- Statistik perpanjangan

### Laporan
- Laporan perpanjangan per periode
- Analisis revenue dari perpanjangan

### Notifikasi
- Notifikasi saat perpanjangan berhasil
- Reminder jatuh tempo baru

## Testing

Untuk testing fitur:

1. **Jalankan Migration**
```bash
php artisan migrate
```

2. **Jalankan Seeder (Opsional)**
```bash
php artisan db:seed --class=PawnExtensionSeeder
```

3. **Test Manual**
- Buat transaksi gadai
- Coba perpanjang dari detail transaksi
- Verifikasi perhitungan biaya
- Test cetak bukti perpanjangan

## Troubleshooting

### Error "Route not found"
- Pastikan routes sudah ditambahkan di `routes/web.php`
- Clear route cache: `php artisan route:clear`

### Error "Class not found"
- Pastikan autoload: `composer dump-autoload`
- Check namespace di controller dan model

### Error Database
- Pastikan migration sudah dijalankan
- Check foreign key constraints

## Pengembangan Lanjutan

### Fitur yang Bisa Ditambahkan
1. **Pembayaran Online** - Integrasi payment gateway
2. **SMS/Email Notification** - Notifikasi otomatis ke nasabah
3. **Approval Workflow** - Persetujuan perpanjangan oleh supervisor
4. **Bulk Extension** - Perpanjangan massal
5. **Extension Limit** - Batasan jumlah perpanjangan per transaksi
6. **Dynamic Fees** - Biaya perpanjangan berdasarkan kategori barang

### Optimisasi
1. **Caching** - Cache perhitungan biaya
2. **Queue Jobs** - Background processing untuk bulk operations
3. **API** - REST API untuk mobile app
4. **Reporting** - Advanced analytics dan reporting

## Kesimpulan

Fitur Perpanjangan Gadai telah berhasil diimplementasi dengan lengkap meliputi:
- ✅ Perhitungan bunga & denda otomatis
- ✅ Proses perpanjangan jatuh tempo
- ✅ Cetak bukti perpanjangan
- ✅ Riwayat perpanjangan
- ✅ Integrasi dengan sistem existing
- ✅ Interface yang user-friendly
- ✅ Konfigurasi yang fleksibel

Sistem siap digunakan dan dapat dikembangkan lebih lanjut sesuai kebutuhan bisnis.