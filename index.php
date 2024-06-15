<?php
// Definir la ruta base
define('BASE_PATH', __DIR__);

// Habilitar la visualización de errores para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Incluir el archivo de rutas
require_once(BASE_PATH . '/routes/routes.user.php');


$init_db = true; // Cambia esto por tu condición real

if ($init_db) {
    $bat_file = realpath(dirname(__FILE__) . '/config/db.bat'); // Ruta al archivo .bat   

    // Ejecutar el archivo .bat y capturar la salida y errores
    $output = [];
    $return_var = 0;
    exec("cmd /c \"$bat_file\" 2>&1", $output, $return_var);
   
}
?>