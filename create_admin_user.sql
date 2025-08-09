-- Script untuk membuat user admin secara manual
-- Jalankan script ini di MySQL/phpMyAdmin

USE pegadaianku;

-- Insert admin user
INSERT INTO users (name, email, email_verified_at, password, role, phone, address, identity_number, is_active, created_at, updated_at) VALUES
('Administrator', 'admin@pegadaianku.com', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567890', 'Jl. Admin No. 1, Jakarta', '1234567890123456', 1, NOW(), NOW());

-- Insert petugas user
INSERT INTO users (name, email, email_verified_at, password, role, phone, address, identity_number, is_active, created_at, updated_at) VALUES
('Petugas Pegadaian', 'petugas@pegadaianku.com', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', '081234567891', 'Jl. Petugas No. 2, Jakarta', '1234567890123457', 1, NOW(), NOW());

-- Password untuk kedua user di atas adalah: password

-- Verifikasi user yang dibuat
SELECT id, name, email, role FROM users;