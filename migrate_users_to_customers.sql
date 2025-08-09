-- Script untuk memigrasikan users dengan role 'nasabah' ke tabel customers
-- HATI-HATI: Backup database terlebih dahulu sebelum menjalankan script ini!

USE pegadaianku;

-- 1. Backup data yang akan diubah (opsional, untuk safety)
CREATE TABLE IF NOT EXISTS backup_pawn_transactions_before_migration AS 
SELECT * FROM pawn_transactions;

-- 2. Insert users dengan role 'nasabah' ke tabel customers
INSERT INTO customers (name, email, phone, address, id_number, id_type, status, created_at, updated_at)
SELECT 
    u.name,
    u.email,
    COALESCE(u.phone, 'N/A') as phone,
    'N/A' as address,
    'N/A' as id_number,
    'ktp' as id_type,
    'active' as status,
    u.created_at,
    u.updated_at
FROM users u
WHERE u.role = 'nasabah'
AND u.id NOT IN (
    -- Pastikan tidak ada duplikasi berdasarkan email
    SELECT DISTINCT email FROM customers WHERE email IS NOT NULL
);

-- 3. Update pawn_transactions untuk menggunakan customer_id yang baru
-- Ini akan mengupdate customer_id dari users.id ke customers.id berdasarkan email yang sama
UPDATE pawn_transactions pt
INNER JOIN users u ON pt.customer_id = u.id AND u.role = 'nasabah'
INNER JOIN customers c ON u.email = c.email
SET pt.customer_id = c.id;

-- 4. Verifikasi hasil migrasi
SELECT 
    'Transaksi dengan customer valid' as status,
    COUNT(*) as total
FROM pawn_transactions pt
INNER JOIN customers c ON pt.customer_id = c.id;

-- 5. Cek apakah masih ada transaksi dengan customer_id yang tidak valid
SELECT 
    pt.id as transaction_id,
    pt.customer_id,
    pt.transaction_code,
    'Customer ID masih tidak valid' as issue
FROM pawn_transactions pt
LEFT JOIN customers c ON pt.customer_id = c.id
WHERE c.id IS NULL;