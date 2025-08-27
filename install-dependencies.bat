@echo off
echo.
echo =========================================
echo   INSTALACION DE DEPENDENCIAS PHP
echo =========================================
echo.

REM Verificar si composer está instalado
where composer >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo ❌ ERROR: Composer no está instalado o no está en el PATH
    echo.
    echo Para instalar Composer:
    echo 1. Descarga desde: https://getcomposer.org/download/
    echo 2. Instala siguiendo las instrucciones
    echo 3. Reinicia tu terminal
    echo.
    pause
    exit /b 1
)

echo ✅ Composer encontrado
echo.

REM Instalar dependencias de production
echo 🔄 Instalando dependencias de producción...
composer install --no-dev --optimize-autoloader

if %ERRORLEVEL% neq 0 (
    echo ❌ ERROR: Falló la instalación de dependencias
    echo.
    pause
    exit /b 1
)

echo.
echo ✅ ¡Dependencias instaladas exitosamente!
echo.
echo Dependencias instaladas:
echo • PHPMailer - Para envío de emails
echo • Dotenv - Para variables de environment  
echo • Autoloader optimizado para producción
echo.
echo Próximos pasos:
echo 1. Copia .env.example a .env
echo 2. Configura tus variables de environment
echo 3. Configura tu servidor web
echo.
pause
