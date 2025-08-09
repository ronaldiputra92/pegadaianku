@echo off
echo ========================================
echo MEMPERBAIKI UPLOAD FOTO DI HALAMAN EDIT
echo ========================================
echo.

echo 1. Memperbaiki method update di controller...
php fix_update_method.php

echo.
echo 2. Membersihkan cache Laravel...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ========================================
echo PERBAIKAN SELESAI!
echo ========================================
echo.
echo Silakan test upload foto baru di:
echo http://127.0.0.1:8000/transactions/3/edit
echo.
pause