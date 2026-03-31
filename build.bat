@echo off
chcp 65001 >nul 2>&1
setlocal EnableDelayedExpansion

:: ============================================================
:: Build Script — Kasir Warung → PHP Desktop (.exe)
:: ============================================================

echo.
echo   ========================================
echo     Build Kasir Warung → PHP Desktop
echo   ========================================
echo.

set "PROJECT_DIR=%~dp0"
set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"
set "PHPDESKTOP_DIR=%PROJECT_DIR%\phpdesktop"
set "DIST_DIR=%PROJECT_DIR%\dist"
set "APP=KasirWarung"

:: --- Cek PHP Desktop ---
if not exist "%PHPDESKTOP_DIR%\phpdesktop-chrome.exe" (
    if not exist "%PHPDESKTOP_DIR%\phpdesktop-chrome-*.exe" (
        echo   [X] Folder phpdesktop\ belum ada atau exe tidak ditemukan.
        echo.
        echo   Download: https://github.com/nicengi/phpdesktop/releases
        echo   Ekstrak ke: %PHPDESKTOP_DIR%\
        echo.
        pause
        exit /b 1
    )
)
echo   [OK] PHP Desktop ditemukan

:: --- Cek php.ini untuk SQLite ---
if exist "%PHPDESKTOP_DIR%\php\php.ini" (
    findstr /C:";extension=pdo_sqlite" "%PHPDESKTOP_DIR%\php\php.ini" >nul 2>&1
    if !errorlevel!==0 (
        echo   [!] PERINGATAN: pdo_sqlite mungkin belum aktif di php.ini
        echo       Buka: %PHPDESKTOP_DIR%\php\php.ini
        echo       Hapus titik koma di depan: extension=pdo_sqlite
        echo.
    )
)

:: --- Bersihkan dist lama ---
if exist "%DIST_DIR%\%APP%" rmdir /s /q "%DIST_DIR%\%APP%"
mkdir "%DIST_DIR%\%APP%"
echo   [OK] Folder dist\ dibersihkan

:: --- Salin PHP Desktop ---
xcopy "%PHPDESKTOP_DIR%\*" "%DIST_DIR%\%APP%\" /E /I /Q /Y >nul
echo   [OK] PHP Desktop base disalin

:: --- settings.json ---
copy /Y "%PROJECT_DIR%\phpdesktop-settings.json" "%DIST_DIR%\%APP%\settings.json" >nul
echo   [OK] settings.json dikonfigurasi

:: --- Bangun www/ ---
if exist "%DIST_DIR%\%APP%\www" rmdir /s /q "%DIST_DIR%\%APP%\www"
mkdir "%DIST_DIR%\%APP%\www"

:: Salin semua ke www/
xcopy "%PROJECT_DIR%\config"      "%DIST_DIR%\%APP%\www\config\"      /E /I /Q /Y >nul
xcopy "%PROJECT_DIR%\controllers" "%DIST_DIR%\%APP%\www\controllers\" /E /I /Q /Y >nul
xcopy "%PROJECT_DIR%\models"      "%DIST_DIR%\%APP%\www\models\"      /E /I /Q /Y >nul
xcopy "%PROJECT_DIR%\views"       "%DIST_DIR%\%APP%\www\views\"       /E /I /Q /Y >nul
xcopy "%PROJECT_DIR%\public\*"    "%DIST_DIR%\%APP%\www\"             /E /I /Q /Y >nul
mkdir "%DIST_DIR%\%APP%\www\database" 2>nul
mkdir "%DIST_DIR%\%APP%\www\uploads"  2>nul
echo   [OK] Aplikasi disalin ke www\

:: --- Fix path ---
echo   [~] Menyesuaikan path...

:: Fix index.php, api.php, struk.php — ganti '/../ jadi '/
powershell -Command "(Get-Content '%DIST_DIR%\%APP%\www\index.php') -replace \"__DIR__ \. '\/\.\.\//\", \"__DIR__ . '/\" | Set-Content -Encoding UTF8 '%DIST_DIR%\%APP%\www\index.php'"
powershell -Command "(Get-Content '%DIST_DIR%\%APP%\www\api.php') -replace \"__DIR__ \. '\/\.\.\//\", \"__DIR__ . '/\" | Set-Content -Encoding UTF8 '%DIST_DIR%\%APP%\www\api.php'"
powershell -Command "(Get-Content '%DIST_DIR%\%APP%\www\struk.php') -replace \"__DIR__ \. '\/\.\.\//\", \"__DIR__ . '/\" | Set-Content -Encoding UTF8 '%DIST_DIR%\%APP%\www\struk.php'"

echo   [OK] Path disesuaikan

echo.
echo   ========================================
echo   BUILD SELESAI!
echo   ========================================
echo.
echo   Output  : %DIST_DIR%\%APP%\
echo   Jalankan: double-click phpdesktop-chrome.exe
echo.
echo   Distribusi: ZIP folder %APP%\ lalu kirim ke client
echo.

pause
