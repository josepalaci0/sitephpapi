<?php

class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->loadEnv();
    }

    private function loadEnv() {
        $envPath = realpath(dirname(__FILE__) . '/../config/.env');
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                switch ($name) {
                    case 'DB_HOST':
                        $this->host = $value;
                        break;
                    case 'DB_PORT':
                        $this->port = $value;
                        break;
                    case 'DB_NAME':
                        $this->db_name = $value;
                        break;
                    case 'DB_USER':
                        $this->username = $value;
                        break;
                    case 'DB_PASS':
                        $this->password = $value;
                        break;
                }
            }
        }
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
