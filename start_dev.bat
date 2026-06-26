@echo off
TITLE RealLife RPG Dev Environment
color 0A

echo ===================================================
echo    Khoi dong Moi truong Phat trien RealLife RPG
echo ===================================================

set BACKEND_DIR=C:\Laravel\RealLife_RPG\RealLife_RPG
set MOBILE_DIR=C:\Laravel\RealLife_RPG\RealLife_RPG_Mobile

echo [1/6] Dang bat May ao Android (Pixel_6a)...
set "ANDROID_HOME=%LOCALAPPDATA%\Android\Sdk"
set "PATH=%PATH%;%ANDROID_HOME%\emulator;%ANDROID_HOME%\platform-tools"
start "Android Emulator" cmd /k "emulator -avd Pixel_6a -no-snapshot-load"

echo [2/6] Dang bat Backend Server (Port 8000)...
start "Laravel Serve" cmd /k "cd /d %BACKEND_DIR% && php artisan serve --port=8000 --no-reload"

echo [3/6] Dang bat Queue Worker...
start "Queue Worker" cmd /k "cd /d %BACKEND_DIR% && php artisan queue:work"

echo [4/6] Dang bat Task Scheduler...
start "Task Scheduler" cmd /k "cd /d %BACKEND_DIR% && php artisan schedule:work"

echo [5/6] Dang bat Reverb WebSocket (Port 8080)...
start "Reverb WebSocket" cmd /k "cd /d %BACKEND_DIR% && php artisan reverb:start"

echo Cho 10 giay de he thong on dinh truoc khi bat Frontend...
timeout /t 10

echo [6/6] Dang bat Frontend (Expo)...
start "Expo Frontend" cmd /k "cd /d %MOBILE_DIR% && npx expo start -c"

echo ===================================================
echo Xong! Tat ca cac dich vu da duoc bat o cac cua so rieng!
echo.
echo ===================================================
echo DE TAT TAT CA DICH VU: Hay nhan phim bat ky vao cua so nay!
echo (Hoac nhan Ctrl + C)
echo ===================================================
pause >nul

echo Dang don dep va tat cac dich vu...
taskkill /F /FI "WINDOWTITLE eq Android Emulator*" /T >nul 2>&1
taskkill /F /FI "WINDOWTITLE eq Laravel Serve*" /T >nul 2>&1
taskkill /F /FI "WINDOWTITLE eq Queue Worker*" /T >nul 2>&1
taskkill /F /FI "WINDOWTITLE eq Task Scheduler*" /T >nul 2>&1
taskkill /F /FI "WINDOWTITLE eq Reverb WebSocket*" /T >nul 2>&1
taskkill /F /FI "WINDOWTITLE eq Expo Frontend*" /T >nul 2>&1

echo Da dong tat ca cac cua so!
pause
