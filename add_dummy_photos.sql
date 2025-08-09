-- Script untuk menambahkan foto dummy ke transaksi
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- Update transaksi dengan ID 1 untuk menambahkan foto dummy
UPDATE pawn_transactions 
SET item_photos = '["dummy_photo_1.jpg", "dummy_photo_2.jpg"]'
WHERE id = 1;

-- Verifikasi update
SELECT 
    id,
    transaction_code,
    item_name,
    item_photos
FROM pawn_transactions 
WHERE id = 1;