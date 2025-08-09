@echo off
echo Setting up Pegadaianku System...
echo.

echo Installing dependencies...
call composer install
echo.

echo Generating application key...
call php artisan key:generate
echo.

echo Running migrations...
call php artisan migrate:fresh
echo.

echo Seeding database...
call php artisan db:seed
echo.

echo Installing Breeze authentication...
call composer require laravel/breeze --dev
call php artisan breeze:install blade
call npm install
call npm run build
echo.

echo Setup completed!
echo.
echo You can now access the application at: http://localhost:8000
echo.
echo Demo accounts:
echo Admin: admin@pegadaianku.com / password
echo Petugas: petugas@pegadaianku.com / password
echo Nasabah: nasabah@pegadaianku.com / password
echo.
echo To start the development server, run: php artisan serve
pause