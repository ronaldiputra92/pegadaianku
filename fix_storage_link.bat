@echo off
echo ===============================================
echo           FIXING STORAGE LINK ISSUE
echo ===============================================
echo.

echo [1/4] Removing existing storage link...
if exist "public\storage" (
    rmdir /s /q "public\storage"
    echo ✓ Existing storage link removed
) else (
    echo ✓ No existing storage link found
)

echo.
echo [2/4] Creating new storage link...
php artisan storage:link
echo.

echo [3/4] Checking if link was created...
if exist "public\storage" (
    echo ✓ Storage link created successfully
) else (
    echo ✗ Failed to create storage link
)

echo.
echo [4/4] Testing file access...
if exist "storage\app\public\customer-documents" (
    echo ✓ Source directory exists
    dir "storage\app\public\customer-documents"
) else (
    echo ✗ Source directory not found
)

echo.
if exist "public\storage\customer-documents" (
    echo ✓ Public link directory exists
    dir "public\storage\customer-documents"
) else (
    echo ✗ Public link directory not found
)

echo.
echo ===============================================
echo                   COMPLETED
echo ===============================================
echo.
echo If the issue persists, try accessing the image directly:
echo http://127.0.0.1:8000/storage/customer-documents/[filename]
echo.
pause