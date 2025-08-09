# MANUAL FIX - STEP BY STEP

## 🚨 MASALAH
Migration error karena tabel `users` sudah ada, dan foreign key constraint masih merujuk ke tabel `users` bukan `customers`.

## 🛠️ SOLUSI MANUAL (PILIH SALAH SATU)

### **OPSI 1: FIX FOREIGN KEY SAJA (RECOMMENDED)**

1. **Buka phpMyAdmin** atau MySQL client
2. **Pilih database** `pegadaianku`
3. **Jalankan SQL berikut** satu per satu:

```sql
-- Step 1: Cek foreign key yang ada
SHOW CREATE TABLE pawn_transactions;

-- Step 2: Drop foreign key lama
ALTER TABLE pawn_transactions DROP FOREIGN KEY pawn_transactions_customer_id_foreign;

-- Step 3: Tambah foreign key baru ke tabel customers
ALTER TABLE pawn_transactions 
ADD CONSTRAINT pawn_transactions_customer_id_foreign 
FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;

-- Step 4: Ubah estimated_value menjadi nullable
ALTER TABLE pawn_transactions 
MODIFY COLUMN estimated_value DECIMAL(15,2) NULL;
```

### **OPSI 2: FIX MIGRATION TABLE**

Jika ingin tetap menggunakan `php artisan migrate`:

1. **Buka phpMyAdmin**
2. **Jalankan SQL berikut**:

```sql
-- Buat tabel migrations jika belum ada
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert migration records untuk tabel yang sudah ada
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_reset_tokens_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1);
```

3. **Kemudian jalankan**:
```bash
php artisan migrate
```

### **OPSI 3: RESET MIGRATION (HATI-HATI!)**

⚠️ **WARNING: Ini akan menghapus semua data!**

```bash
php artisan migrate:fresh --seed
```

## 🔍 VERIFIKASI

Setelah menjalankan salah satu opsi di atas:

1. **Cek foreign key**:
```sql
SELECT 
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = 'pegadaianku' 
    AND TABLE_NAME = 'pawn_transactions' 
    AND COLUMN_NAME = 'customer_id';
```

2. **Cek customer yang tersedia**:
```sql
SELECT id, name, email FROM customers ORDER BY id;
```

3. **Test create transaction** di `http://127.0.0.1:8000/transactions/create`

## 📋 REKOMENDASI

**Gunakan OPSI 1** karena:
- ✅ Paling aman (tidak menghapus data)
- ✅ Langsung mengatasi masalah utama
- ✅ Cepat dan efektif

Setelah menjalankan OPSI 1, coba lagi simpan transaksi - seharusnya sudah berhasil!