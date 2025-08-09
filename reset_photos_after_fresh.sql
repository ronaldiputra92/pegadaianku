-- Reset foto transaksi setelah migrate:fresh
USE pegadaianku;

-- Opsi 1: Hapus semua referensi foto (transaksi tanpa foto)
UPDATE pawn_transactions SET item_photos = NULL;

-- Opsi 2: Atau set sebagai array kosong
-- UPDATE pawn_transactions SET item_photos = '[]';

-- Verifikasi perubahan
SELECT 
    id,
    transaction_code,
    item_name,
    item_photos
FROM pawn_transactions;