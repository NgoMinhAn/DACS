@echo off
echo ============================================
echo Finding Laragon Installation Path
echo ============================================
echo.
echo Searching for Laragon on all drives...
echo.

set FOUND=0

for %%d in (C D E F G H I J K L M N O P Q R S T U V W X Y Z) do (
    if exist "%%d:\laragon\etc\apache2\httpd.conf" (
        echo [FOUND] Laragon Apache config at: %%d:\laragon\etc\apache2\httpd.conf
        echo.
        echo Your Laragon path is: %%d:\laragon
        echo.
        echo Virtual host files should be placed in:
        echo   %%d:\laragon\etc\apache2\sites-enabled\
        echo.
        set FOUND=1
    )
    if exist "%%d:\Laragon\etc\apache2\httpd.conf" (
        echo [FOUND] Laragon Apache config at: %%d:\Laragon\etc\apache2\httpd.conf
        echo.
        echo Your Laragon path is: %%d:\Laragon
        echo.
        echo Virtual host files should be placed in:
        echo   %%d:\Laragon\etc\apache2\sites-enabled\
        echo.
        set FOUND=1
    )
)

if %FOUND% equ 0 (
    echo [NOT FOUND] Could not locate Laragon automatically.
    echo.
    echo Please find it manually:
    echo 1. Open Laragon
    echo 2. Click Menu -^> Tools -^> Open Apache httpd.conf
    echo 3. Note the path shown in the address bar
    echo.
    echo Or search for "httpd.conf" in File Explorer
    echo and look for a file in a "laragon\etc\apache2\" folder
)

echo.
echo ============================================
pause

