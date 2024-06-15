<?php

/**
 * Autentica la solicitud verificando la clave API.
 * 
 * @param array $data Datos de la solicitud.
 * @return true|string Devuelve true si la autenticación es exitosa, de lo contrario devuelve un mensaje de error.
 */
function authenticateRequest($data) {
    $apiKey = 'your_api_key_here'; // Reemplaza con tu clave API real

    if (!isset($data['api_key']) || $data['api_key'] !== $apiKey) {
        return 'Autenticación fallida: clave API inválida';
    }

    return true;
}

/**
 * Verifica que los datos obligatorios estén presentes en la solicitud.
 * 
 * @param array $data Datos de la solicitud.
 * @param array $requiredFields Campos obligatorios.
 * @return true|string Devuelve true si todos los campos obligatorios están presentes, de lo contrario devuelve un mensaje de error.
 */
function validateRequiredFields($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            return "El campo '$field' es obligatorio";
        }
    }

    return true;
}

/**
 * Escapa los datos de entrada para prevenir inyecciones SQL.
 * 
 * @param string $input Datos de entrada.
 * @return string Datos de entrada escapados.
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}
?>
