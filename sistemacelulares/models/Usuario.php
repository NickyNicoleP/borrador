<?php
/**
 * Modelo para gestionar usuarios
 */

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;
    public $activo;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Verificar credenciales de usuario
    public function login($email, $password) {
        $query = "SELECT id, nombre, email, password, rol, activo 
                  FROM " . $this->table_name . " 
                  WHERE email = :email AND activo = 1 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña
            if(password_verify($password, $row['password'])) {
                // Actualizar última conexión (opcional)
                $this->actualizarUltimaConexion($row['id']);
                
                return $row;
            }
        }

        return false;
    }

    // Actualizar última conexión
    private function actualizarUltimaConexion($user_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
    }

    // Crear nuevo usuario
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, email=:email, password=:password, rol=:rol";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));

        // Encriptar contraseña
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":rol", $this->rol);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Verificar si email existe
    public function emailExiste($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Obtener todos los usuarios
    public function leerTodos() {
        $query = "SELECT id, nombre, email, rol, activo, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>