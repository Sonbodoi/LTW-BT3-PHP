@echo off
cd /d "%~dp0"
if not exist .env (
  echo CHUA CO .env. HAY CHAY CAI_DAT_LAN_DAU.cmd TRUOC.
  pause
  exit /b 1
)
if not exist vendor\autoload.php (
  echo CHUA CO THU VIEN LARAVEL. HAY CHAY CAI_DAT_LAN_DAU.cmd TRUOC.
  pause
  exit /b 1
)
echo WEB UNIVERSAL TEA: http://127.0.0.1:8000
echo Nhan Ctrl+C de dung web.
php artisan serve
pause
