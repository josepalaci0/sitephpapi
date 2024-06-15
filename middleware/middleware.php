<?php

/**
 * Middleware para validar el token JWT.
 * 
 * @param string $token El token JWT recibido.
 * @return bool Devuelve true si el token es válido, de lo contrario false.
 */
function validateToken($token) {
    $tokenParts = explode('.', $token);
    
    if (count($tokenParts) !== 3) {
        return false; // El token no tiene el formato esperado
    }
    
    $header = json_decode(base64_decode($tokenParts[0]), true);
    $payload = json_decode(base64_decode($tokenParts[1]), true);
    $signature = base64_decode($tokenParts[2]);
    
    if (!$header || !$payload) {
        return false; // Error al decodificar header o payload
    }
    
    // Verificar la expiración del token
    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false; // El token ha expirado
    }
    
    $key = 'your_secret_key'; // La misma clave secreta usada para generar el token
    $expectedSignature = hash_hmac('sha256', "$tokenParts[0].$tokenParts[1]", $key, true);
    
    if (hash_equals($signature, $expectedSignature)) {
        return $payload;
    } else {
        return false;
    }
}

/**
 * Obtener el token JWT del encabezado Authorization.
 * 
 * @return string|null El token JWT si está presente, de lo contrario null.
 */
function getBearerToken() {
    // Obtener todos los encabezados de la solicitud
    $headers = getallheaders();

    // Verificar si existe el encabezado de autorización
    if (isset($headers['Authorization'])) {
        // Buscar el token en el encabezado de autorización
        $matches = array();
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return $matches[1];
        }
    }

    // No se encontró el token
    return null;
}

/**
 * Middleware para aplicar la validación del token.
 * 
 * @param callable $next La función "siguiente" middleware o controlador.
 */
function applyMiddleware($next) {
    // Obtener el token de la solicitud
    $token = getBearerToken();

    // Verificar el token
    if ($token && validateToken($token)) {
        // Si el token es válido, continuar con el siguiente middleware o controlador
        call_user_func($next);
    } else {
        // Si el token no es válido, devolver un error
        echo json_encode(['error' => 'Acceso no autorizado']);
        http_response_code(401);
    }
}
?>