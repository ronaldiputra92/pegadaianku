# Fitur Transaksi Gadai Baru

## Ringkasan Fitur yang Ditambahkan

Sistem transaksi gadai telah diperbarui dengan fitur-fitur baru yang lebih lengkap dan profesional:

### 1. Input Data Barang Gadai yang Lengkap

#### Fitur Baru:
- **Kondisi Barang**: Pilihan kondisi (Baik, Rusak Ringan, Rusak Berat)
- **Upload Foto Barang**: Maksimal 5 foto dengan format JPG, PNG, JPEG (max 2MB per foto)
- **Informasi Detail**: Berat barang, deskripsi lengkap

#### Cara Penggunaan:
1. Buka halaman "Buat Transaksi Baru"
2. Isi informasi barang dengan lengkap
3. Upload foto barang dengan drag & drop atau klik upload
4. Pilih kondisi barang sesuai keadaan fisik

### 2. Penilaian Barang oleh Petugas

#### Fitur Baru:
- **Nilai Pasar**: Input nilai pasar saat ini
- **Nilai Taksir Petugas**: Penilaian resmi untuk keperluan gadai
- **LTV Ratio**: Persentase maksimal pinjaman dari nilai taksir (default 80%)
- **Catatan Penilaian**: Penjelasan detail hasil penilaian
- **Perhitungan Otomatis**: Maksimal pinjaman dihitung otomatis

#### Cara Penggunaan:
1. Dari detail transaksi, klik tombol "Nilai Barang"
2. Isi nilai pasar berdasarkan kondisi barang
3. Tentukan nilai taksir untuk keperluan gadai
4. Atur LTV ratio sesuai kebijakan
5. Tambahkan catatan penilaian yang detail
6. Sistem akan menghitung maksimal pinjaman otomatis

### 3. Perhitungan Pinjaman dan Bunga Otomatis

#### Fitur Baru:
- **Biaya Admin**: Input biaya administrasi
- **Biaya Asuransi**: Input biaya asuransi barang
- **Pinjaman Bersih**: Perhitungan otomatis (pinjaman - biaya)
- **Estimasi Real-time**: Perhitungan langsung saat input data

#### Cara Penggunaan:
1. Input jumlah pinjaman yang diinginkan
2. Atur suku bunga sesuai kebijakan
3. Tambahkan biaya admin dan asuransi jika ada
4. Sistem akan menampilkan:
   - Bunga per bulan
   - Total bunga
   - Total yang harus dibayar
   - Pinjaman bersih yang diterima

### 4. Cetak Bukti Transaksi Gadai

#### Fitur Baru:
- **Template Profesional**: Bukti transaksi dengan format resmi
- **Informasi Lengkap**: Semua detail transaksi, penilaian, dan perhitungan
- **Nomor Bukti Unik**: Auto-generate nomor bukti untuk setiap cetak
- **Export PDF**: Download bukti dalam format PDF
- **Foto Barang**: Menampilkan foto barang dalam bukti

#### Cara Penggunaan:
1. Dari detail transaksi, klik tombol "Cetak Bukti"
2. Sistem akan generate PDF otomatis
3. File akan ter-download dengan nama: `bukti_gadai_{kode_transaksi}.pdf`
4. Bukti dapat dicetak atau disimpan digital

### 5. Tanda Tangan Digital (Opsional)

#### Fitur Baru:
- **Canvas Signature**: Area tanda tangan digital untuk nasabah dan petugas
- **Touch Support**: Mendukung tanda tangan di perangkat mobile
- **Validasi**: Memastikan kedua pihak sudah tanda tangan
- **Penyimpanan Base64**: Tanda tangan disimpan sebagai gambar
- **Integrasi Bukti**: Tanda tangan muncul di bukti transaksi

#### Cara Penggunaan:
1. Setelah penilaian selesai, klik tombol "Tanda Tangan"
2. Nasabah tanda tangan di area kiri
3. Petugas tanda tangan di area kanan
4. Centang persetujuan syarat dan ketentuan
5. Klik "Simpan Tanda Tangan"

## Alur Proses Transaksi Baru

### 1. Pembuatan Transaksi
```
Input Data Nasabah → Input Data Barang → Upload Foto → Set Pinjaman Awal → Simpan
```

### 2. Penilaian Barang
```
Buka Detail Transaksi → Klik "Nilai Barang" → Input Penilaian → Hitung Max Pinjaman → Simpan
```

### 3. Tanda Tangan Digital
```
Setelah Penilaian → Klik "Tanda Tangan" → TTD Nasabah → TTD Petugas → Setuju S&K → Simpan
```

### 4. Cetak Bukti
```
Klik "Cetak Bukti" → Generate PDF → Download/Print
```

## Database Changes

### Tabel `pawn_transactions` - Field Baru:

```sql
-- Item details
item_condition VARCHAR(255) -- Kondisi barang
item_photos JSON -- Array foto barang

-- Appraisal
market_value DECIMAL(15,2) -- Nilai pasar
appraisal_value DECIMAL(15,2) -- Nilai taksir petugas
appraisal_notes TEXT -- Catatan penilaian
appraised_at TIMESTAMP -- Waktu penilaian
appraiser_id BIGINT -- ID penilai

-- Loan calculation
loan_to_value_ratio DECIMAL(5,2) -- LTV ratio
admin_fee DECIMAL(15,2) -- Biaya admin
insurance_fee DECIMAL(15,2) -- Biaya asuransi

-- Digital signature
customer_signature TEXT -- Base64 tanda tangan nasabah
officer_signature TEXT -- Base64 tanda tangan petugas
signed_at TIMESTAMP -- Waktu tanda tangan

-- Receipt
receipt_printed BOOLEAN -- Status cetak bukti
receipt_printed_at TIMESTAMP -- Waktu cetak
receipt_number VARCHAR(255) -- Nomor bukti
```

## Routes Baru

```php
// Penilaian
GET  /transactions/{transaction}/appraise
POST /transactions/{transaction}/appraise

// Tanda Tangan Digital
GET  /transactions/{transaction}/signature
POST /transactions/{transaction}/signature

// Cetak Bukti
GET  /transactions/{transaction}/receipt

// API Perhitungan
POST /transactions/calculate-loan
```

## File Baru yang Dibuat

### Views:
- `resources/views/transactions/appraise.blade.php` - Form penilaian barang
- `resources/views/transactions/signature.blade.php` - Form tanda tangan digital
- `resources/views/transactions/receipt.blade.php` - Template bukti transaksi

### Migration:
- `database/migrations/2024_01_15_000001_add_item_fields_to_pawn_transactions_table.php`

### Updated Files:
- `app/Models/PawnTransaction.php` - Tambah field dan method baru
- `app/Http/Controllers/PawnTransactionController.php` - Tambah method untuk fitur baru
- `resources/views/transactions/create.blade.php` - Form input yang diperluas
- `resources/views/transactions/show.blade.php` - Tampilan detail yang lengkap
- `routes/web.php` - Route untuk fitur baru

## Cara Menjalankan Update

1. **Jalankan Migration**:
   ```bash
   php artisan migrate
   ```
   Atau double-click file `run_migration.bat`

2. **Buat Storage Link** (jika belum):
   ```bash
   php artisan storage:link
   ```

3. **Install DomPDF** (untuk cetak PDF):
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

## Keamanan dan Validasi

### Upload File:
- Validasi tipe file (hanya gambar)
- Batas ukuran file (max 2MB)
- Sanitasi nama file
- Penyimpanan di storage/app/public

### Tanda Tangan Digital:
- Validasi format Base64
- Cek keberadaan kedua tanda tangan
- Timestamp untuk audit trail

### Penilaian:
- Validasi nilai numerik
- Cek LTV ratio dalam batas wajar
- Audit trail penilai dan waktu

## Fitur Tambahan yang Bisa Dikembangkan

1. **Notifikasi Real-time**: WebSocket untuk update status
2. **Barcode/QR Code**: Untuk tracking barang
3. **Integrasi Payment Gateway**: Untuk pembayaran online
4. **Mobile App**: Aplikasi mobile untuk nasabah
5. **Backup Otomatis**: Backup foto dan dokumen ke cloud
6. **Laporan Analytics**: Dashboard analisis transaksi

## Troubleshooting

### Error Upload Foto:
- Pastikan folder `storage/app/public/transaction_photos` ada
- Cek permission folder (755)
- Pastikan storage link sudah dibuat

### Error PDF Generation:
- Install DomPDF: `composer require barryvdh/laravel-dompdf`
- Cek memory limit PHP untuk file besar

### Error Tanda Tangan:
- Pastikan JavaScript enabled
- Cek browser compatibility untuk Canvas API
- Clear browser cache jika ada masalah

## Support

Untuk pertanyaan atau masalah terkait fitur baru ini, silakan hubungi tim development atau buat issue di repository project.