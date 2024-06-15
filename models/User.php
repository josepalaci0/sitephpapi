<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $nombre;
    public $apellido;
    public $edad;
    public $sexo;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, edad=:edad, sexo=:sexo, email=:email, password=:password, estado=:estado";
        $stmt = $this->conn->prepare($query);

        // Sanitiza los datos antes de insertarlos en la base de datos para prevenir inyección de SQL
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->edad = intval($this->edad);
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Enlaza los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":sexo", $this->sexo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":estado", $this->estado);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, apellido = :apellido, edad = :edad, sexo = :sexo, email = :email, password = :password, estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitiza los datos antes de insertarlos en la base de datos para prevenir inyección de SQL
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->edad = intval($this->edad);
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Enlaza los parámetros
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":sexo", $this->sexo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":estado", $this->estado);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // En lugar de eliminar el registro, simplemente cambiamos el estado a 'inactivo'
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Enlaza los parámetros
        $stmt->bindParam(":id", $this->id);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    

    public function Login(){
        $query = "SELECT password, estado FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
    
        // Sanitiza los datos antes de insertarlos en la base de datos para prevenir inyección de SQL
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        // Enlaza los parámetros
        $stmt->bindParam(":email", $this->email);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado como un arreglo asociativo
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verifica si se encontró un resultado
        if ($row) {
            // Verifica el estado del usuario
            if ($row['estado'] === 'activo') {
                return $row['password']; // Devuelve el hash de la contraseña
            } else {
                return null; // El usuario no está activo, podrías manejar esto como desees
            }
        } else {
            return null; // El usuario no existe en la base de datos
        }
    }
    function getIdByEmail($password, $email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
    
        // Sanitiza los datos antes de insertarlos en la base de datos para prevenir inyección de SQL
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        // Enlaza los parámetros
        $stmt->bindParam(":email", $this->email);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado como un arreglo asociativo
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verifica si se encontró un resultado
        if ($row) {
            return $row['id']; // Devuelve el ID del usuario
        } else {
            return null; // El usuario no existe en la base de datos
        }
        
    }
    
    
}
?>
