@echo off
echo ===============================================
echo           PEGADAIANKU - REMINDER SYSTEM
echo ===============================================
echo.

echo [INFO] Menjalankan sistem pengingat jatuh tempo...
echo.

echo [1/3] Mengirim pengingat jatuh tempo...
php artisan reminder:due-date
echo.

echo [2/3] Menghitung denda keterlambatan...
php artisan penalty:calculate
echo.

echo [3/3] Mengirim pengingat overdue...
php artisan reminder:overdue
echo.

echo ===============================================
echo           REMINDER SYSTEM COMPLETED
echo ===============================================
echo.
echo Sistem pengingat telah selesai dijalankan.
echo Cek log untuk detail hasil pengiriman.
echo.
pause