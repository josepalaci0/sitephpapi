@echo off
SETLOCAL ENABLEDELAYEDEXPANSION

REM Ruta al archivo .env
set ENV_FILE=C:\xampp\htdocs\sitephp\config\.env
REM Ruta al archivo .sql
set SQL_FILE=C:\xampp\htdocs\sitephp\config\db.sql
REM Ruta al ejecutable de MySQL
set MYSQL_EXE=C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe

REM Verificar que el archivo mysql.exe exista
if not exist "!MYSQL_EXE!" (
    echo No se encontró mysql.exe en "!MYSQL_EXE!"
    pause
    exit /b 1
)

REM Leer el archivo .env y establecer las variables de entorno
FOR /F "tokens=* delims=" %%i IN ('type "!ENV_FILE!"') DO (
    SET "line=%%i"
    FOR /F "tokens=1,2 delims==" %%a IN ("!line!") DO (
        SET "%%a=%%b"
    )
)

REM Imprimir los valores de las variables para verificar que se están leyendo correctamente
echo MYSQL_USER=!DB_USER!
echo MYSQL_PASSWORD=!DB_PASS!
echo MYSQL_DATABASE=!DB_NAME!
echo MYSQL_PORT=!DB_PORT!
echo Ejecutando: "!MYSQL_EXE!" -u !DB_USER! -p!DB_PASS! -P !DB_PORT! !DB_NAME! < !SQL_FILE!

REM Ejecutar el archivo SQL
"!MYSQL_EXE!" -u !DB_USER! -p!DB_PASS! -P !DB_PORT! !DB_NAME! < !SQL_FILE!

if %ERRORLEVEL% neq 0 (
    echo Fallo al ejecutar el archivo SQL
) else (
    echo Archivo SQL ejecutado correctamente
)

echo Operaciones completadas.
pause
ENDLOCAL

