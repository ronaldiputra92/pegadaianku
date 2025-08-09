@echo off
echo Fixing Payment System Issues...
echo.

echo 1. Running migrations...
php artisan migrate --force
echo.

echo 2. Clearing all caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo 3. Checking routes...
php artisan route:list --name=payments
echo.

echo 4. Testing database...
php artisan tinker --execute="echo 'Testing DB: '; try { echo \App\Models\PawnTransaction::count() . ' transactions found'; } catch(Exception \$e) { echo 'ERROR: ' . \$e->getMessage(); }"
echo.

echo System fixed! Try accessing the payment form again.
echo Test URL: http://127.0.0.1:8000/payments/test-api
echo.
pause