-- Script untuk mengecek foto transaksi
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- Cek transaksi dengan ID 1
SELECT 
    id,
    transaction_code,
    item_name,
    item_photos,
    created_at
FROM pawn_transactions 
WHERE id = 1;

-- Cek semua transaksi yang memiliki foto
SELECT 
    id,
    transaction_code,
    item_name,
    item_photos,
    created_at
FROM pawn_transactions 
WHERE item_photos IS NOT NULL 
AND item_photos != '[]' 
AND item_photos != 'null';

-- Cek total transaksi
SELECT COUNT(*) as total_transactions FROM pawn_transactions;