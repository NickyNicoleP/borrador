<?php
/**
 * Modelo para gestionar suscriptores del newsletter
 */

class Newsletter {
    private $conn;
    private $table_name = "newsletter_suscriptores";

    public $id;
    public $email;
    public $activo;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Suscribir email
    public function suscribir() {
        // Verificar si el email ya existe
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Email ya existe, actualizar a activo
            $query = "UPDATE " . $this->table_name . " SET activo = 1 WHERE email = :email";
        } else {
            // Nuevo email
            $query = "INSERT INTO " . $this->table_name . " SET email=:email, activo=1";
        }

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Obtener todos los suscriptores activos
    public function obtenerSuscriptoresActivos() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activo = 1 ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>