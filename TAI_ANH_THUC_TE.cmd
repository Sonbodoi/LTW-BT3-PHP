@echo off
setlocal
cd /d "%~dp0"
echo ===============================================
echo   UNIVERSAL TEA - TAI ANH CHUP THUC TE
 echo ===============================================
 echo Can ket noi Internet cho lan tai anh dau tien.
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\download_real_photos.ps1"
if errorlevel 1 (
  echo.
  echo Mot so anh chua tai duoc. Anh SVG cu van hien thi; ban co the thu lai.
) else (
  echo.
  echo Da tai du anh. Mo website va an Ctrl+F5 de xem anh moi.
)
pause
