@echo off
echo Running database migration...
php artisan migrate
echo.
echo Migration completed!
pause