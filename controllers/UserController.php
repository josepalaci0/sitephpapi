<?php
require_once (BASE_PATH . '../config/database.php'); ;
require_once(BASE_PATH ."../models/User.php");

class UserController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUsers() {
        $user = new User($this->conn);
        $stmt = $user->read();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['users' => $users]);
    }

    public function getUserById($id) {
        $user = new User($this->conn);
        $user->id = $id;
        $user_data = $user->readOne();
        echo json_encode(['user' => $user_data]);
    }

    public function addUser($data) {
        if (isset($data['nombre']) && isset($data['apellido']) && isset($data['edad']) && isset($data['sexo']) && isset($data['email']) && isset($data['password'])) {
            $user = new User($this->conn);
            $user->nombre = $data['nombre'];
            $user->apellido = $data['apellido'];
            $user->edad = $data['edad'];
            $user->sexo = $data['sexo'];
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
            $user->estado = isset($data['estado']) ? $data['estado'] : 'activo';
    
            if ($user->create()) {
                echo json_encode(['message' => 'Usuario registrado exitosamente']);
            } else {
                echo json_encode(['error' => 'Error al registrar el usuario']);
            }
        } else {
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }
    
    public function updateUser($id, $data) {
        if (!$id) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }
    
        $required_fields = ['nombre', 'apellido', 'edad', 'sexo', 'email', 'password'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                echo json_encode(['error' => 'Datos incompletos']);
                return;
            }
        }
    
        $user = new User($this->conn);
        $user->id = $id;
        $user->nombre = $data['nombre'];
        $user->apellido = $data['apellido'];
        $user->edad = $data['edad'];
        $user->sexo = $data['sexo'];
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->estado = isset($data['estado']) ? $data['estado'] : 'activo';
    
        if ($user->update()) {
            echo json_encode(['message' => 'Usuario actualizado exitosamente']);
        } else {
            echo json_encode(['error' => 'Error al actualizar el usuario']);
        }
    }
    

    public function deleteUser($id) {
        if (!$id) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $user = new User($this->conn);
        $user->id = $id;

        if ($user->delete()) {
            echo json_encode(['message' => 'Usuario eliminado exitosamente']);
        } else {
            echo json_encode(['error' => 'Error al eliminar el usuario']);
        }
    }

    public function login($data) {
        if (isset($data['email']) && isset($data['password'])) {
            $user = new User($this->conn);
            $user->email = $data['email'];
            
            // Obtén el hash almacenado en la base de datos para el usuario dado
            $stored_hash = $user->login(); // Suponiendo que esta función retorna el hash almacenado
            
            // Verificar si la contraseña ingresada coincide con el hash almacenado
            if (password_verify($data['password'], $stored_hash)) { 
                // Clave secreta para firmar el token (manejar esto de manera segura)
                $key = 'your_secret_key';
    
                // Datos que quieres incluir en el payload del token
                $user_id = $user->getIdByEmail( $user->password = password_hash($data['password'], PASSWORD_BCRYPT), $user->email = $data['email']); // Esto debería ser el ID del usuario que inició sesión
                $expiration = time() + 150; 
    
                // Construye el payload del token
                $payload = array(
                    'iat' => time(), // Tiempo en que se emitió el token
                    'exp' => $expiration, // Tiempo de expiración del token
                    'user_id' => $user_id, // ID del usuario u otra información que desees incluir
                );
    
                // Codifica el payload en formato JSON
                $payload_base64 = base64_encode(json_encode($payload));
    
                // Crea el header del token
                $header = base64_encode(json_encode(array('typ' => 'JWT', 'alg' => 'HS256')));
    
                // Genera la firma HMAC usando SHA256
                $signature = hash_hmac('sha256', "$header.$payload_base64", $key, true);
                $signature_base64 = base64_encode($signature);
    
                // Construye el token JWT
                $token = "$header.$payload_base64.$signature_base64";
    
                // Devuelve el token en formato JSON como parte de la respuesta
                echo json_encode(array(
                    'message' => 'Inicio de sesión exitoso',
                    'token' => $token
                ));
            } else {
                // La contraseña es incorrecta
                echo json_encode(array(
                    'error' => 'Credenciales incorrectas'
                ));
            }
        } else {
            // Datos incompletos
            echo json_encode(array(
                'error' => 'Datos incompletos'
            ));
        }
    }    
    
}
