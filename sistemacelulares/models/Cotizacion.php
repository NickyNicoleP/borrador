<?php
/**
 * Modelo para gestionar las cotizaciones
 */

class Cotizacion {
    private $conn;
    private $table_name = "cotizaciones";

    public $id;
    public $tipo_servicio;
    public $datos_gb;
    public $minutos;
    public $sms;
    public $precio_final;
    public $plan_recomendado;
    public $email;
    public $telefono;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Guardar cotización
    public function guardar() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET tipo_servicio=:tipo_servicio, datos_gb=:datos_gb, minutos=:minutos, 
                     sms=:sms, precio_final=:precio_final, plan_recomendado=:plan_recomendado,
                     email=:email, telefono=:telefono";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->tipo_servicio = htmlspecialchars(strip_tags($this->tipo_servicio));
        $this->datos_gb = htmlspecialchars(strip_tags($this->datos_gb));
        $this->minutos = htmlspecialchars(strip_tags($this->minutos));
        $this->sms = htmlspecialchars(strip_tags($this->sms));
        $this->precio_final = htmlspecialchars(strip_tags($this->precio_final));
        $this->plan_recomendado = htmlspecialchars(strip_tags($this->plan_recomendado));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Vincular valores
        $stmt->bindParam(":tipo_servicio", $this->tipo_servicio);
        $stmt->bindParam(":datos_gb", $this->datos_gb);
        $stmt->bindParam(":minutos", $this->minutos);
        $stmt->bindParam(":sms", $this->sms);
        $stmt->bindParam(":precio_final", $this->precio_final);
        $stmt->bindParam(":plan_recomendado", $this->plan_recomendado);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Leer todas las cotizaciones
    public function leer() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Obtener estadísticas
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_cotizaciones,
                    AVG(precio_final) as precio_promedio,
                    COUNT(DISTINCT email) as clientes_unicos,
                    tipo_servicio,
                    COUNT(*) as cantidad_por_tipo
                  FROM " . $this->table_name . " 
                  GROUP BY tipo_servicio";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>