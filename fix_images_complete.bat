@echo off
echo ========================================
echo PERBAIKAN LENGKAP MASALAH FOTO TRANSAKSI
echo ========================================
echo.

echo 1. Testing akses langsung ke file...
php test_direct_access.php

echo.
echo 2. Memperbaiki permission dan konfigurasi...
php fix_permissions.php

echo.
echo 3. Membersihkan cache Laravel...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo ========================================
echo TESTING SELESAI!
echo ========================================
echo.
echo Silakan buka browser dan test:
echo 1. http://127.0.0.1:8000/comprehensive_test.html
echo 2. http://127.0.0.1:8000/test_image.html
echo 3. http://127.0.0.1:8000/transactions/2
echo.
echo Jika masih bermasalah, restart server dengan:
echo php artisan serve --host=127.0.0.1 --port=8000
echo.
pause