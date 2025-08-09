# Perhitungan Biaya Perpanjangan Gadai

## Overview
Sistem perpanjangan gadai menghitung biaya berdasarkan 3 komponen utama:
1. **Bunga Perpanjangan** - Bunga untuk periode perpanjangan
2. **Denda Keterlambatan** - Denda jika transaksi sudah melewati jatuh tempo
3. **Biaya Administrasi** - Biaya tetap untuk setiap perpanjangan

## Formula Perhitungan

### 1. Bunga Perpanjangan
```
Bunga Perpanjangan = Jumlah Pinjaman × Suku Bunga × Periode Perpanjangan
```

**Contoh:**
- Jumlah Pinjaman: Rp 4,000,000
- Suku Bunga: 2.5% per bulan
- Periode Perpanjangan: 3 bulan

```
Bunga = Rp 4,000,000 × 2.5% × 3 bulan
Bunga = Rp 4,000,000 × 0.025 × 3
Bunga = Rp 300,000
```

### 2. Denda Keterlambatan
Denda hanya dikenakan jika transaksi sudah melewati jatuh tempo.

```
Denda = Jumlah Pinjaman × Rate Denda Harian × Jumlah Hari Terlambat
```

**Default Rate:** 0.1% per hari (0.001)

**Contoh:**
- Jumlah Pinjaman: Rp 4,000,000
- Jatuh Tempo: 10 Januari 2025
- Tanggal Perpanjangan: 15 Januari 2025
- Hari Terlambat: 5 hari

```
Denda = Rp 4,000,000 × 0.1% × 5 hari
Denda = Rp 4,000,000 × 0.001 × 5
Denda = Rp 20,000
```

### 3. Biaya Administrasi
Biaya tetap yang dikenakan untuk setiap perpanjangan.

**Default:** Rp 50,000 per perpanjangan

### 4. Total Biaya
```
Total Biaya = Bunga Perpanjangan + Denda Keterlambatan + Biaya Administrasi
```

**Contoh Lengkap:**
```
Bunga Perpanjangan    = Rp 300,000
Denda Keterlambatan   = Rp 20,000
Biaya Administrasi    = Rp 50,000
------------------------
Total Biaya           = Rp 370,000
```

## Implementasi dalam Kode

### Model PawnExtension
```php
public static function calculateExtensionFees($transaction, $extensionMonths)
{
    // Ensure extension months is integer
    $extensionMonths = (int) $extensionMonths;
    
    // Calculate interest for extension period
    $monthlyInterestRate = $transaction->interest_rate / 100;
    $interestAmount = $transaction->loan_amount * $monthlyInterestRate * $extensionMonths;

    // Calculate penalty if overdue
    $penaltyAmount = 0;
    if ($transaction->due_date < Carbon::now()) {
        $overdueDays = Carbon::now()->diffInDays($transaction->due_date);
        $dailyPenaltyRate = config('pawn.penalty_rate_per_day', 0.001); // 0.1% per day
        $penaltyAmount = $transaction->loan_amount * $dailyPenaltyRate * $overdueDays;
    }

    // Admin fee for extension (configurable)
    $adminFee = config('pawn.extension_admin_fee', 50000); // Default Rp 50,000

    $totalAmount = $interestAmount + $penaltyAmount + $adminFee;

    return [
        'interest_amount' => $interestAmount,
        'penalty_amount' => $penaltyAmount,
        'admin_fee' => $adminFee,
        'total_amount' => $totalAmount,
    ];
}
```

## Konfigurasi

File `config/pawn.php` berisi pengaturan yang dapat disesuaikan:

```php
'extension_admin_fee' => env('PAWN_EXTENSION_ADMIN_FEE', 50000), // Rp 50,000
'penalty_rate_per_day' => env('PAWN_PENALTY_RATE_PER_DAY', 0.001), // 0.1% per hari
```

### Environment Variables
Tambahkan di file `.env` untuk kustomisasi:

```env
PAWN_EXTENSION_ADMIN_FEE=50000
PAWN_PENALTY_RATE_PER_DAY=0.001
```

## Contoh Skenario Perhitungan

### Skenario 1: Perpanjangan Tepat Waktu
**Data Transaksi:**
- Jumlah Pinjaman: Rp 5,000,000
- Suku Bunga: 3% per bulan
- Jatuh Tempo: 20 Januari 2025
- Tanggal Perpanjangan: 18 Januari 2025 (2 hari sebelum jatuh tempo)
- Periode Perpanjangan: 2 bulan

**Perhitungan:**
```
Bunga Perpanjangan = Rp 5,000,000 × 3% × 2 = Rp 300,000
Denda Keterlambatan = Rp 0 (belum terlambat)
Biaya Admin = Rp 50,000
Total = Rp 350,000
```

### Skenario 2: Perpanjangan Terlambat
**Data Transaksi:**
- Jumlah Pinjaman: Rp 3,000,000
- Suku Bunga: 2% per bulan
- Jatuh Tempo: 15 Januari 2025
- Tanggal Perpanjangan: 25 Januari 2025 (10 hari terlambat)
- Periode Perpanjangan: 1 bulan

**Perhitungan:**
```
Bunga Perpanjangan = Rp 3,000,000 × 2% × 1 = Rp 60,000
Denda Keterlambatan = Rp 3,000,000 × 0.1% × 10 = Rp 30,000
Biaya Admin = Rp 50,000
Total = Rp 140,000
```

### Skenario 3: Perpanjangan Jangka Panjang
**Data Transaksi:**
- Jumlah Pinjaman: Rp 10,000,000
- Suku Bunga: 2.5% per bulan
- Jatuh Tempo: 10 Januari 2025
- Tanggal Perpanjangan: 12 Januari 2025 (2 hari terlambat)
- Periode Perpanjangan: 6 bulan (maksimal)

**Perhitungan:**
```
Bunga Perpanjangan = Rp 10,000,000 × 2.5% × 6 = Rp 1,500,000
Denda Keterlambatan = Rp 10,000,000 × 0.1% × 2 = Rp 20,000
Biaya Admin = Rp 50,000
Total = Rp 1,570,000
```

## Validasi dan Batasan

### Batasan Sistem
1. **Periode Perpanjangan:** Minimum 1 bulan, maksimum 6 bulan
2. **Status Transaksi:** Hanya transaksi dengan status 'active', 'extended', atau 'overdue' yang dapat diperpanjang
3. **Jumlah Perpanjangan:** Tidak ada batasan jumlah perpanjangan

### Validasi Input
- Extension months harus integer antara 1-6
- Transaction ID harus valid dan ada di database
- Transaksi harus memiliki customer yang valid

## Dampak Perpanjangan

### Pada Transaksi
1. **Jatuh Tempo:** Diperbarui sesuai periode perpanjangan
2. **Status:** Berubah menjadi 'extended'
3. **Loan Period:** Bertambah sesuai periode perpanjangan

### Pada Sistem
1. **Riwayat:** Semua perpanjangan tercatat dalam tabel `pawn_extensions`
2. **Audit Trail:** Lengkap dengan petugas, tanggal, dan detail biaya
3. **Bukti:** Generate bukti perpanjangan dalam format PDF

## Tips Optimasi

### Untuk Nasabah
1. **Perpanjang Sebelum Jatuh Tempo:** Hindari denda keterlambatan
2. **Pilih Periode Optimal:** Sesuaikan dengan kemampuan finansial
3. **Simpan Bukti:** Bukti perpanjangan penting untuk referensi

### Untuk Petugas
1. **Verifikasi Data:** Pastikan data transaksi akurat sebelum perpanjangan
2. **Jelaskan Biaya:** Berikan penjelasan detail perhitungan kepada nasabah
3. **Cetak Bukti:** Selalu cetak bukti perpanjangan untuk nasabah

## Troubleshooting

### Error Umum
1. **Carbon Error:** Pastikan extension_months berupa integer
2. **Transaction Not Found:** Verifikasi kode transaksi
3. **Invalid Status:** Cek status transaksi sebelum perpanjangan

### Debugging
```php
// Log perhitungan biaya
Log::info('Extension calculation', [
    'transaction_id' => $transaction->id,
    'extension_months' => $extensionMonths,
    'interest_rate' => $transaction->interest_rate,
    'loan_amount' => $transaction->loan_amount,
    'calculated_fees' => $fees
]);
```

## Kesimpulan

Sistem perhitungan biaya perpanjangan gadai dirancang untuk:
- **Transparan:** Formula perhitungan jelas dan dapat diverifikasi
- **Fleksibel:** Dapat dikonfigurasi sesuai kebijakan perusahaan
- **Akurat:** Perhitungan otomatis mengurangi kesalahan manual
- **Audit-able:** Semua perhitungan tercatat dan dapat dilacak

Sistem ini memastikan bahwa setiap perpanjangan gadai dihitung dengan fair dan konsisten, memberikan kepercayaan kepada nasabah dan kemudahan bagi petugas dalam mengelola perpanjangan gadai.