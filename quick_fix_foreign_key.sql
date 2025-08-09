-- Script untuk memperbaiki foreign key constraint secara langsung
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- 1. Cek foreign key constraint yang ada
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = 'pegadaianku' 
    AND TABLE_NAME = 'pawn_transactions' 
    AND COLUMN_NAME = 'customer_id'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- 2. Drop foreign key constraint yang lama
-- Ganti nama constraint sesuai dengan hasil query di atas
ALTER TABLE pawn_transactions DROP FOREIGN KEY pawn_transactions_customer_id_foreign;

-- 3. Tambahkan foreign key constraint yang baru ke tabel customers
ALTER TABLE pawn_transactions 
ADD CONSTRAINT pawn_transactions_customer_id_foreign 
FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;

-- 4. Verifikasi perubahan
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = 'pegadaianku' 
    AND TABLE_NAME = 'pawn_transactions' 
    AND COLUMN_NAME = 'customer_id'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- 5. Cek apakah customer dengan ID 6 ada di tabel customers
SELECT id, name, email, phone FROM customers WHERE id = 6;

-- 6. Jika customer ID 6 tidak ada, tampilkan semua customers yang tersedia
SELECT id, name, email, phone FROM customers ORDER BY id;