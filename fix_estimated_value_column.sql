-- Script untuk mengubah kolom estimated_value menjadi nullable
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- Ubah kolom estimated_value menjadi nullable
ALTER TABLE pawn_transactions 
MODIFY COLUMN estimated_value DECIMAL(15,2) NULL;

-- Verifikasi perubahan
DESCRIBE pawn_transactions;