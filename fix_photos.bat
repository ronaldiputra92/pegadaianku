@echo off
echo ========================================
echo MEMPERBAIKI MASALAH FOTO TRANSAKSI
echo ========================================
echo.

echo 1. Menjalankan script perbaikan foto...
php fix_photo_paths.php

echo.
echo 2. Membuat storage link Laravel...
php artisan storage:link

echo.
echo 3. Mengoptimalkan cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ========================================
echo PERBAIKAN SELESAI!
echo ========================================
echo.
echo Silakan cek kembali halaman:
echo http://127.0.0.1:8000/transactions/2
echo.
pause