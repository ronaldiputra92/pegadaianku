-- Cek data transaksi saat ini setelah migrate:fresh
USE pegadaianku;

-- Cek semua transaksi dan foto mereka
SELECT 
    id,
    transaction_code,
    item_name,
    item_photos,
    created_at
FROM pawn_transactions 
ORDER BY id;

-- Cek apakah ada transaksi tanpa foto
SELECT COUNT(*) as transactions_without_photos
FROM pawn_transactions 
WHERE item_photos IS NULL OR item_photos = '[]' OR item_photos = 'null';

-- Cek apakah ada transaksi dengan foto
SELECT COUNT(*) as transactions_with_photos
FROM pawn_transactions 
WHERE item_photos IS NOT NULL AND item_photos != '[]' AND item_photos != 'null';