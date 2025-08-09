@echo off
echo Installing required dependencies for new transaction features...
echo.

echo 1. Installing DomPDF for PDF generation...
composer require barryvdh/laravel-dompdf
echo.

echo 2. Creating storage link for file uploads...
php artisan storage:link
echo.

echo 3. Running database migration...
php artisan migrate
echo.

echo 4. Clearing application cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo.

echo Installation completed!
echo.
echo New features available:
echo - Enhanced item data input with photos
echo - Item appraisal by officers
echo - Automatic loan calculation
echo - Digital signature
echo - Professional receipt printing
echo.
pause