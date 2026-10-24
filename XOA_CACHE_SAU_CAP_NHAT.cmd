@echo off
cd /d "%~dp0"
echo =============================================
echo UNIVERSAL TEA - CAP NHAT DANG NHAP CHUNG
echo =============================================
echo Don cache Laravel sau khi thay source.
php artisan optimize:clear
if errorlevel 1 goto fail
echo.
echo THANH CONG. Hay mo CHAY_WEB.cmd va truy cap:
echo http://127.0.0.1:8000/login
echo.
pause
exit /b 0
:fail
echo.
echo CO LOI. Hay dam bao da cai Composer va file .env ton tai.
pause
exit /b 1
