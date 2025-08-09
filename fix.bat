@echo off
echo Fixing common Laravel issues...
echo.

echo Clearing application cache...
php artisan cache:clear
echo.

echo Clearing configuration cache...
php artisan config:clear
echo.

echo Clearing view cache...
php artisan view:clear
echo.

echo Clearing route cache...
php artisan route:clear
echo.

echo Optimizing application...
php artisan optimize
echo.

echo Generating application key (if needed)...
php artisan key:generate
echo.

echo All fixes applied!
echo.
echo If you still have issues, try:
echo 1. Check if MySQL is running
echo 2. Verify database credentials in .env
echo 3. Run: migrate.bat
echo.
pause