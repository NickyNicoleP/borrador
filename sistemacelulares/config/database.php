<?php
/**
 * Configuración de la base de datos
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'conectaplus';
    private $username = 'root';  // Cambiar por tu usuario de MySQL
    private $password = '';      // Cambiar por tu contraseña de MySQL
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>