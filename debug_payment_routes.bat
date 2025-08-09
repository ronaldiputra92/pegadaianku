@echo off
echo Debugging Payment Routes...
echo.

echo 1. Checking if routes are registered...
php artisan route:list --name=payments
echo.

echo 2. Clearing all caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo 3. Testing database connection...
php artisan tinker --execute="echo 'DB Connection: '; try { \DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }"
echo.

echo 4. Checking if PawnTransaction model works...
php artisan tinker --execute="echo 'PawnTransaction count: ' . \App\Models\PawnTransaction::count();"
echo.

echo Debug completed!
pause