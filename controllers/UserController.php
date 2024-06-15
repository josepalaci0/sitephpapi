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
}
