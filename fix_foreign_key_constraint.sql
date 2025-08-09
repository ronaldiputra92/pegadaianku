-- Script untuk memperbaiki foreign key constraint customer_id
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- 1. Cek nama constraint yang ada
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

-- 2. Drop foreign key constraint yang lama (ganti CONSTRAINT_NAME dengan nama yang ditemukan di step 1)
-- Contoh: ALTER TABLE pawn_transactions DROP FOREIGN KEY pawn_transactions_customer_id_foreign;
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