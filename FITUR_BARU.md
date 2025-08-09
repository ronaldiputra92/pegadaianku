# Fitur Baru: Manajemen Dokumen dan Riwayat Nasabah

## 📋 Deskripsi Fitur

Telah ditambahkan fitur baru di sidebar untuk mengelola data nasabah secara lebih lengkap:

### 🆕 **Fitur yang Ditambahkan:**

1. **Registrasi dan Data Lengkap Nasabah**
   - Form registrasi nasabah yang lebih komprehensif
   - Data lengkap termasuk KTP, alamat, pekerjaan, dll.

2. **Upload Dokumen KTP/Identitas**
   - Upload berbagai jenis dokumen (KTP, SIM, Passport, KK, NPWP)
   - Preview dokumen (gambar dan PDF)
   - Verifikasi dokumen oleh admin/petugas
   - Download dokumen

3. **Riwayat Transaksi Gadai**
   - Melihat semua transaksi nasabah
   - Riwayat pembayaran lengkap
   - Statistik transaksi nasabah
   - Filter berdasarkan periode dan status

## 🎯 **Struktur Menu Sidebar Baru:**

```
📊 Dashboard
💰 Transaksi
💳 Pembayaran
👥 Nasabah (Dropdown)
  ├── 📋 Data Nasabah
  ├── ➕ Registrasi Nasabah
  ├── 🆔 Dokumen KTP
  └── 📈 Riwayat Transaksi
📊 Laporan
👤 Pengguna (Admin only)
```

## 🔧 **File yang Dibuat/Dimodifikasi:**

### Controllers:
- `app/Http/Controllers/CustomerDocumentController.php`
- `app/Http/Controllers/CustomerHistoryController.php`

### Models:
- `app/Models/Customer.php`
- `app/Models/CustomerDocument.php`

### Views:
- `resources/views/customer-documents/index.blade.php`
- `resources/views/customer-documents/create.blade.php`
- `resources/views/customer-documents/show.blade.php`
- `resources/views/customer-history/index.blade.php`

### Migrations:
- `database/migrations/2024_01_15_000000_create_customers_table.php`
- `database/migrations/2024_01_15_000001_create_customer_documents_table.php`

### Routes:
- Ditambahkan routes untuk customer documents dan history di `routes/web.php`

### Layout:
- Diperbarui sidebar di `resources/views/layouts/app.blade.php`

## 📱 **Fitur Dokumen Nasabah:**

### Upload Dokumen:
- ✅ Drag & drop file upload
- ✅ Preview file sebelum upload
- ✅ Validasi tipe file (JPG, PNG, PDF)
- ✅ Maksimal ukuran file 5MB
- ✅ Metadata lengkap (nama file, ukuran, tipe)

### Manajemen Dokumen:
- ✅ Daftar semua dokumen dengan filter
- ✅ Preview dokumen (gambar langsung, PDF di tab baru)
- ✅ Download dokumen
- ✅ Verifikasi dokumen oleh petugas
- ✅ Edit informasi dokumen
- ✅ Hapus dokumen

### Jenis Dokumen yang Didukung:
- 🆔 KTP (Kartu Tanda Penduduk)
- 🚗 SIM (Surat Izin Mengemudi)
- ✈️ Passport
- 👨‍👩‍👧‍👦 KK (Kartu Keluarga)
- 💼 NPWP

## 📊 **Fitur Riwayat Transaksi:**

### Dashboard Riwayat:
- ✅ Pilih nasabah dari dropdown
- ✅ Statistik lengkap transaksi nasabah
- ✅ Tab terpisah untuk transaksi dan pembayaran
- ✅ Filter berdasarkan status dan tanggal

### Statistik yang Ditampilkan:
- 📈 Total transaksi
- 💰 Total pinjaman
- ⏰ Transaksi aktif
- 💳 Total pembayaran
- 📅 Tanggal transaksi pertama/terakhir
- 💵 Rata-rata pinjaman

### Fitur Lanjutan:
- ✅ Detail lengkap setiap transaksi
- ✅ Riwayat pembayaran per transaksi
- ✅ Export data (dalam pengembangan)
- ✅ Filter berdasarkan periode

## 🚀 **Cara Menggunakan:**

### 1. Upload Dokumen:
1. Klik menu "Nasabah" → "Dokumen KTP"
2. Klik tombol "Upload Dokumen"
3. Pilih nasabah dan jenis dokumen
4. Upload file dengan drag & drop atau browse
5. Tambahkan catatan jika diperlukan
6. Klik "Upload Dokumen"

### 2. Verifikasi Dokumen:
1. Buka daftar dokumen
2. Klik ikon mata untuk melihat detail
3. Klik tombol "Verifikasi" jika dokumen valid
4. Status akan berubah menjadi "Terverifikasi"

### 3. Melihat Riwayat Transaksi:
1. Klik menu "Nasabah" → "Riwayat Transaksi"
2. Pilih nasabah dari dropdown
3. Lihat statistik dan riwayat lengkap
4. Gunakan tab untuk beralih antara transaksi dan pembayaran

## 🔒 **Keamanan:**

- ✅ Validasi file upload (tipe dan ukuran)
- ✅ Penyimpanan file di storage/app/public
- ✅ Akses terbatas berdasarkan role
- ✅ Validasi input form
- ✅ CSRF protection

## 📋 **TODO / Pengembangan Selanjutnya:**

- [ ] Export riwayat ke PDF/Excel
- [ ] Notifikasi dokumen yang perlu diverifikasi
- [ ] Bulk upload dokumen
- [ ] OCR untuk ekstrak data dari KTP
- [ ] Backup otomatis dokumen
- [ ] Audit trail untuk perubahan dokumen

## 🎨 **UI/UX Improvements:**

- ✅ Sidebar dropdown dengan animasi
- ✅ Icons yang konsisten
- ✅ Loading states
- ✅ Responsive design
- ✅ Drag & drop interface
- ✅ Preview dokumen
- ✅ Status badges
- ✅ Statistik cards

## 🔧 **Setup Database:**

Jalankan migration untuk membuat tabel baru:

```bash
php artisan migrate
```

Opsional, jalankan seeder untuk data contoh:

```bash
php artisan db:seed --class=CustomerSeeder
```

Pastikan symbolic link untuk storage sudah dibuat:

```bash
php artisan storage:link
```

## 📞 **Support:**

Jika ada pertanyaan atau masalah dengan fitur baru ini, silakan hubungi tim development.