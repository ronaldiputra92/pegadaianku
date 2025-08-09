@echo off
echo Running database migrations and seeders...
echo.

echo Creating fresh database...
php artisan migrate:fresh
echo.

echo Seeding database with sample data...
php artisan db:seed
echo.

echo Database setup completed!
echo.
echo Demo accounts created:
echo Admin: admin@pegadaianku.com / password
echo Petugas: petugas@pegadaianku.com / password
echo Nasabah: nasabah@pegadaianku.com / password
echo.
pause