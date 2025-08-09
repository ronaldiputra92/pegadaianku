@echo off
echo Fixing Customer Documents Issue...
echo ================================

echo.
echo 1. Clearing application cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo 2. Running migrations...
php artisan migrate --force

echo.
echo 3. Checking if storage link exists...
php artisan storage:link

echo.
echo 4. Dumping autoload...
composer dump-autoload

echo.
echo 5. Testing routes...
php artisan route:list | findstr customer-documents

echo.
echo Done! Try accessing http://127.0.0.1:8000/customer-documents now.
echo.
echo If still not working, try these test URLs:
echo - http://127.0.0.1:8000/customer-documents-test
echo - http://127.0.0.1:8000/customer-documents-simple
echo - http://127.0.0.1:8000/test-customer-documents

pause