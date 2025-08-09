@echo off
echo Updating Payment System with Pelunasan Features...
echo.

echo 1. Running new payment migration...
php artisan migrate
echo.

echo 2. Clearing application cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo.

echo Payment system updated successfully!
echo.
echo New Payment Features Available:
echo - Pelunasan penuh (Full Payment)
echo - Pembayaran sebagian (Partial Payment)
echo - Pembayaran bunga saja (Interest Only)
echo - Metode pembayaran: Tunai, Transfer, Kartu Debit/Kredit
echo - Perhitungan total kewajiban otomatis
echo - Cetak bukti pelunasan
echo - Tracking sisa tagihan
echo.
pause