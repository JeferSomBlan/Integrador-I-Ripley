@echo off
:: Establecer la ruta a PHP y a los scripts
set PHP_PATH=C:\xampp\php\php.exe
set PROJECT_PATH=C:\xampp\htdocs\Ripley\util

:: Ejecutar el script de backup de la base de datos
echo Ejecutando backup de base de datos...
%PHP_PATH% %PROJECT_PATH%\backup_db.php

:: Ejecutar el script de backup del proyecto
echo Ejecutando backup del proyecto...
%PHP_PATH% %PROJECT_PATH%\backup_project.php

:: Pausar para depuración (puedes quitar esta línea)
pause
