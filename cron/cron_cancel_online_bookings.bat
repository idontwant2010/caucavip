@echo off
setlocal

REM === Paths ===
set "PHP_EXE=C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"
set "CRON_PHP=C:\laragon\www\caucavip\cron\cron_cancel_online_bookings.php"
set "LOG_DIR=C:\laragon\www\caucavip\logs"

REM === Ngày theo định dạng yyyy-MM-dd an toàn cho tên file ===
for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd"') do set TODAY=%%i
set "LOG_FILE=%LOG_DIR%\cron_cancel_online_bookings_%TODAY%.log"

if not exist "%LOG_DIR%" mkdir "%LOG_DIR%"

REM Ví dụ chạy mỗi 15 phút, có thể thêm tham số:
REM   --grace=60  (số phút quá hạn)
REM   --limit=200 (số booking tối đa mỗi lần)
REM   --dry=1     (chạy thử, không ghi DB)  --> bỏ khi chạy thật
REM   --debug=1   (in thêm log)
"%PHP_EXE%" "%CRON_PHP%" --grace=60 --limit=200 --debug=1 >> "%LOG_FILE%" 2>&1

endlocal
