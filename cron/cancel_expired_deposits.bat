@echo off
setlocal

set "PHP_EXE=C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"
set "CRON_PHP=C:\laragon\www\caucavip\cron\cancel_expired_deposits.php"
set "LOG_DIR=C:\laragon\www\caucavip\logs"

for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd"') do set TODAY=%%i
set "LOG_FILE=%LOG_DIR%\cancel_expired_deposits_%TODAY%.log"

if not exist "%LOG_DIR%" mkdir "%LOG_DIR%"

"%PHP_EXE%" "%CRON_PHP%" --minutes=30 --debug=1 >> "%LOG_FILE%" 2>&1

endlocal
