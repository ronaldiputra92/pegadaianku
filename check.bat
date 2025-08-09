@echo off
echo Checking Pegadaianku Application Status...
echo.

echo ===============================================
echo                 SYSTEM CHECK
echo ===============================================
echo.

echo Checking PHP version...
php --version
echo.

echo Checking Laravel version...
php artisan --version
echo.

echo Checking database connection...
php artisan migrate:status
echo.

echo Checking routes...
php artisan route:list --compact
echo.

echo ===============================================
echo                 FILE CHECK
echo ===============================================
echo.

echo Checking important files...
if exist "routes\auth.php" (
    echo [✓] routes\auth.php - OK
) else (
    echo [✗] routes\auth.php - MISSING
)

if exist "app\Http\Controllers\Auth\AuthenticatedSessionController.php" (
    echo [✓] AuthenticatedSessionController - OK
) else (
    echo [✗] AuthenticatedSessionController - MISSING
)

if exist "app\Http\Controllers\DashboardController.php" (
    echo [✓] DashboardController - OK
) else (
    echo [✗] DashboardController - MISSING
)

if exist "resources\views\layouts\app.blade.php" (
    echo [✓] Layout template - OK
) else (
    echo [✗] Layout template - MISSING
)

if exist "resources\views\auth\login.blade.php" (
    echo [✓] Login view - OK
) else (
    echo [✗] Login view - MISSING
)

echo.
echo ===============================================
echo                 NEXT STEPS
echo ===============================================
echo.
echo 1. If all checks pass, run: run.bat
echo 2. If database issues, run: migrate.bat
echo 3. If cache issues, run: fix.bat
echo 4. Access application at: http://localhost:8000
echo.
pause