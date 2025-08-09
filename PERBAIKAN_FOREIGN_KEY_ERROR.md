# Perbaikan Error Foreign Key Constraint pada Transaksi Gadai

## Masalah yang Terjadi

Error yang muncul:
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails `pegadaiankupawn_transactions`, CONSTRAINT `pawn_transactions_customer_id_foreign` FOREIGN KEY `customer_id`) REFERENCES `users` `id`) ON DELETE CASCADE)
```

## Penyebab Masalah

1. **Foreign Key Constraint Salah**: Tabel `pawn_transactions` masih memiliki foreign key constraint yang merujuk ke tabel `users`, padahal aplikasi sekarang menggunakan tabel `customers` yang terpisah.

2. **Data Customer Tidak Ada**: Customer dengan ID 3 tidak ada di tabel `users` (yang masih dirujuk oleh constraint lama).

3. **Migration Tidak Lengkap**: Migration untuk mengubah foreign key constraint dari `users` ke `customers` mungkin belum dijalankan atau gagal.

## Struktur Database yang Benar

### Sebelum (Bermasalah):
```sql
pawn_transactions.customer_id -> users.id
```

### Sesudah (Benar):
```sql
pawn_transactions.customer_id -> customers.id
```

## Solusi

### Opsi 1: Jalankan Script Perbaikan Otomatis

```bash
php fix_customer_constraint_simple.php
```

Script ini akan:
1. Membuat customer dengan ID 3 jika belum ada
2. Memperbaiki foreign key constraint
3. Memverifikasi perbaikan

### Opsi 2: Jalankan Migration dan Seeder

```bash
# Jalankan migration baru
php artisan migrate

# Jalankan seeder untuk memastikan data customer ada
php artisan db:seed --class=EnsureCustomerDataSeeder
```

### Opsi 3: Perbaikan Manual via Database

```sql
-- 1. Disable foreign key checks
SET FOREIGN_KEY_CHECKS=0;

-- 2. Drop constraint lama
ALTER TABLE pawn_transactions DROP FOREIGN KEY pawn_transactions_customer_id_foreign;

-- 3. Tambah constraint baru
ALTER TABLE pawn_transactions ADD CONSTRAINT pawn_transactions_customer_id_foreign 
FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;

-- 4. Enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

-- 5. Buat customer jika belum ada
INSERT INTO customers (id, name, email, phone, address, id_number, id_type, gender, status, created_at, updated_at) 
VALUES (3, 'Budi Santoso', 'budi.santoso@example.com', '081234567893', 'Jl. Thamrin No. 3, Jakarta', '3171234567890003', 'ktp', 'male', 'active', NOW(), NOW());
```

## Verifikasi Perbaikan

### 1. Cek Foreign Key Constraint

```sql
SELECT 
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'pawn_transactions' 
AND COLUMN_NAME = 'customer_id' 
AND CONSTRAINT_NAME != 'PRIMARY'
AND TABLE_SCHEMA = DATABASE();
```

Hasil yang benar:
```
CONSTRAINT_NAME: pawn_transactions_customer_id_foreign
REFERENCED_TABLE_NAME: customers
REFERENCED_COLUMN_NAME: id
```

### 2. Cek Data Customer

```sql
SELECT id, name, email, status FROM customers WHERE id = 3;
```

### 3. Test Transaksi Baru

Coba buat transaksi baru melalui form aplikasi dengan customer ID 3.

## Pencegahan Masalah Serupa

1. **Selalu Jalankan Migration**: Pastikan semua migration dijalankan dengan `php artisan migrate`

2. **Backup Database**: Selalu backup database sebelum menjalankan migration yang mengubah foreign key

3. **Test di Development**: Test perubahan database di environment development terlebih dahulu

4. **Monitor Foreign Key**: Gunakan query berikut untuk monitor foreign key constraints:

```sql
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE 
WHERE CONSTRAINT_NAME LIKE '%foreign%'
AND TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME, COLUMN_NAME;
```

## File yang Dibuat untuk Perbaikan

1. `fix_customer_constraint_simple.php` - Script perbaikan cepat
2. `fix_foreign_key_constraint.php` - Script perbaikan lengkap dengan analisis
3. `database/migrations/2025_08_06_000001_ensure_customer_foreign_key_constraint.php` - Migration perbaikan
4. `database/seeders/EnsureCustomerDataSeeder.php` - Seeder untuk data customer

## Kesimpulan

Error ini terjadi karena inkonsistensi antara struktur database (foreign key masih merujuk ke `users`) dengan logika aplikasi (menggunakan tabel `customers`). Setelah menjalankan salah satu solusi di atas, transaksi gadai baru dapat dibuat dengan normal.