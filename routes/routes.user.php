<?php
// Incluir archivos necesarios
require_once(BASE_PATH . '/config/database.php');
require_once(BASE_PATH . '/controllers/UserController.php');

// Obtener la URI y el método HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/sitephp/index.php', '', $uri); // Ajustar según la URL base
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : null;

// Rutas
if ($uri === '/users' && $method === 'GET') {
    $controller = new UserController();
    $controller->getUsers();
} elseif (preg_match('/\/users\/(\d+)/', $uri, $matches) && $method === 'GET') {
    $controller = new UserController();
    $controller->getUserById($matches[1]);
} elseif ($uri === '/users' && $method === 'POST') {
    $controller = new UserController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->addUser($data);
} elseif (preg_match('/\/users\/(\d+)/', $uri, $matches) && $method === 'PUT') {
    $controller = new UserController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateUser($matches[1], $data);
} elseif (preg_match('/\/users\/(\d+)/', $uri, $matches) && $method === 'DELETE') {
    $controller = new UserController();
    $controller->deleteUser($matches[1]);
} else {
    echo json_encode(['error' => 'Ruta no encontrada']);
}
?>