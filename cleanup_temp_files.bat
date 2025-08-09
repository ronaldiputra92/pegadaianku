@echo off
echo ========================================
echo MEMBERSIHKAN FILE TEMPORARY
echo ========================================
echo.

echo Menghapus file-file temporary yang sudah tidak diperlukan...

if exist "fix_photo_paths.php" (
    del "fix_photo_paths.php"
    echo ✓ Dihapus: fix_photo_paths.php
)

if exist "test_photo_urls.php" (
    del "test_photo_urls.php"
    echo ✓ Dihapus: test_photo_urls.php
)

if exist "test_direct_access.php" (
    del "test_direct_access.php"
    echo ✓ Dihapus: test_direct_access.php
)

if exist "fix_permissions.php" (
    del "fix_permissions.php"
    echo ✓ Dihapus: fix_permissions.php
)

if exist "test_server.php" (
    del "test_server.php"
    echo ✓ Dihapus: test_server.php
)

if exist "fix_photos.bat" (
    del "fix_photos.bat"
    echo ✓ Dihapus: fix_photos.bat
)

if exist "fix_photos_final.bat" (
    del "fix_photos_final.bat"
    echo ✓ Dihapus: fix_photos_final.bat
)

if exist "fix_images_complete.bat" (
    del "fix_images_complete.bat"
    echo ✓ Dihapus: fix_images_complete.bat
)

if exist "public\test_image.html" (
    del "public\test_image.html"
    echo ✓ Dihapus: public\test_image.html
)

if exist "public\comprehensive_test.html" (
    del "public\comprehensive_test.html"
    echo ✓ Dihapus: public\comprehensive_test.html
)

if exist "public\serve_image.php" (
    del "public\serve_image.php"
    echo ✓ Dihapus: public\serve_image.php
)

echo.
echo ========================================
echo PEMBERSIHAN SELESAI!
echo ========================================
echo.
echo File-file temporary telah dihapus.
echo Aplikasi sekarang bersih dan siap digunakan.
echo.
pause