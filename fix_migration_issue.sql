-- Script untuk memperbaiki masalah migration
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- 1. Cek apakah tabel migrations ada
SELECT COUNT(*) as migrations_table_exists FROM information_schema.tables 
WHERE table_schema = 'pegadaianku' AND table_name = 'migrations';

-- 2. Jika tabel migrations tidak ada, buat tabel migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Insert migration records untuk tabel yang sudah ada
-- Ini akan menandai bahwa migration sudah dijalankan
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_reset_tokens_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1);

-- 4. Cek migration yang sudah tercatat
SELECT * FROM migrations ORDER BY batch, id;