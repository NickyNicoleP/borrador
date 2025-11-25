<?php
/**
 * Modelo para gestionar las promociones
 */

class Promocion {
    private $conn;
    private $table_name = "promociones";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $vigencia;
    public $activa;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todas las promociones
    public function leer() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Leer promociones activas
    public function leerActivas() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activa = 1 AND vigencia >= CURDATE() ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Crear nueva promoción
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET nombre=:nombre, descripcion=:descripcion, precio=:precio, vigencia=:vigencia, activa=:activa";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->vigencia = htmlspecialchars(strip_tags($this->vigencia));
        $this->activa = htmlspecialchars(strip_tags($this->activa));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":vigencia", $this->vigencia);
        $stmt->bindParam(":activa", $this->activa);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Actualizar promoción
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre=:nombre, descripcion=:descripcion, precio=:precio, vigencia=:vigencia, activa=:activa
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->vigencia = htmlspecialchars(strip_tags($this->vigencia));
        $this->activa = htmlspecialchars(strip_tags($this->activa));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":vigencia", $this->vigencia);
        $stmt->bindParam(":activa", $this->activa);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Eliminar promoción
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>