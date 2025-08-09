@echo off
echo Clearing Laravel caches...

php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo Cache cleared successfully!
pause