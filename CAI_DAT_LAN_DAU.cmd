@echo off
cd /d "%~dp0"
echo === CAI DAT PHP PACKAGES ===
call composer install
if errorlevel 1 goto fail
echo === CAI DAT FRONTEND ===
call npm install
if errorlevel 1 goto fail
if not exist .env (
  copy .env.example .env
  php artisan key:generate
  if errorlevel 1 goto fail
)
if not exist database\database.sqlite type nul > database\database.sqlite
echo === DATABASE + DEMO DATA ===
php artisan migrate --seed
if errorlevel 1 goto fail
php artisan storage:link
echo === BUILD GIAO DIEN ===
call npm run build
if errorlevel 1 goto fail
echo.
echo THANH CONG. BAT WEB BANG CHAY_WEB.cmd
pause
exit /b 0
:fail
echo.
echo CO LOI. CHUP ANH CMD GUI DE DUOC HO TRO.
pause
exit /b 1
