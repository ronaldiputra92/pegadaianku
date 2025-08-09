-- Script untuk mengecek konsistensi data
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- 1. Cek apakah ada transaksi dengan customer_id yang tidak ada di tabel customers
SELECT 
    pt.id as transaction_id,
    pt.customer_id,
    pt.transaction_code,
    'Customer ID tidak ditemukan di tabel customers' as issue
FROM pawn_transactions pt
LEFT JOIN customers c ON pt.customer_id = c.id
WHERE c.id IS NULL;

-- 2. Cek apakah ada transaksi dengan customer_id yang merujuk ke tabel users
SELECT 
    pt.id as transaction_id,
    pt.customer_id,
    pt.transaction_code,
    u.name as user_name,
    u.role as user_role,
    'Customer ID merujuk ke tabel users' as issue
FROM pawn_transactions pt
LEFT JOIN users u ON pt.customer_id = u.id
WHERE u.id IS NOT NULL AND u.role = 'nasabah';

-- 3. Cek total customers di masing-masing tabel
SELECT 'customers' as table_name, COUNT(*) as total FROM customers
UNION ALL
SELECT 'users (role=nasabah)' as table_name, COUNT(*) as total FROM users WHERE role = 'nasabah';

-- 4. Cek apakah customer_id di pawn_transactions ada yang valid di tabel customers
SELECT 
    'Valid customer references' as status,
    COUNT(*) as total
FROM pawn_transactions pt
INNER JOIN customers c ON pt.customer_id = c.id;