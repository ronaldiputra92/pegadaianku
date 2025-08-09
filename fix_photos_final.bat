@echo off
echo ========================================
echo PERBAIKAN FINAL MASALAH FOTO TRANSAKSI
echo ========================================
echo.

echo 1. Membersihkan cache konfigurasi...
php artisan config:clear
php artisan cache:clear

echo.
echo 2. Testing dan memperbaiki URL foto...
php test_photo_urls.php

echo.
echo 3. Restart server development (jika diperlukan)...
echo Silakan restart server dengan: php artisan serve --host=127.0.0.1 --port=8000

echo.
echo ========================================
echo PERBAIKAN SELESAI!
echo ========================================
echo.
echo Silakan cek kembali halaman:
echo http://127.0.0.1:8000/transactions/2
echo.
pause