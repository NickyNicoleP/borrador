<?php
/**
 * Script PHP para procesar la cotización de planes de telefonía móvil
 * Con conexión a base de datos
 */

// Incluir configuración de base de datos y modelos
include_once 'config/database.php';
include_once 'models/Cotizacion.php';

// Headers para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciar objeto Cotizacion
$cotizacion = new Cotizacion($db);

// Simulamos el procesamiento de una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer datos JSON de entrada
    $data = json_decode(file_get_contents("php://input"));

    // Validar que los datos existen
    if (!empty($data->tipo_servicio) && !empty($data->datos) && !empty($data->minutos) && !empty($data->sms)) {
        
        $tipo_servicio = $data->tipo_servicio;
        $datos = intval($data->datos);
        $minutos = intval($data->minutos);
        $sms = intval($data->sms);
        $email = $data->email ?? '';
        $telefono = $data->telefono ?? '';

        // Calcular el precio según el tipo de servicio y características
        $precio = calcularPrecioPlan($tipo_servicio, $datos, $minutos, $sms);
        
        // Determinar el nombre del plan recomendado
        $plan_recomendado = determinarPlanRecomendado($datos, $minutos, $sms);
        
        // Aplicar promociones especiales si corresponde
        $promocion = aplicarPromociones($tipo_servicio, $datos, $precio);
        $precio_final = $precio - $promocion['descuento'];

        // Guardar en base de datos
        $cotizacion->tipo_servicio = $tipo_servicio;
        $cotizacion->datos_gb = $datos;
        $cotizacion->minutos = $minutos;
        $cotizacion->sms = $sms;
        $cotizacion->precio_final = $precio_final;
        $cotizacion->plan_recomendado = $plan_recomendado;
        $cotizacion->email = $email;
        $cotizacion->telefono = $telefono;

        if($cotizacion->guardar()) {
            // Preparar respuesta exitosa
            $respuesta = [
                'success' => true,
                'message' => 'Cotización guardada exitosamente',
                'plan_recomendado' => $plan_recomendado,
                'precio_base' => $precio,
                'precio_final' => $precio_final,
                'promocion' => $promocion['mensaje'],
                'caracteristicas' => [
                    'datos' => $datos,
                    'minutos' => $minutos,
                    'sms' => $sms,
                    'tipo_servicio' => $tipo_servicio
                ]
            ];
            
            echo json_encode($respuesta);
        } else {
            // Error al guardar
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al guardar la cotización']);
        }
        
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos de entrada inválidos']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

// Las funciones de cálculo permanecen igual...
function calcularPrecioPlan($tipo, $datos, $minutos, $sms) {
    $precio = 0;
    
    if ($tipo === 'prepago') {
        $precio = ($datos * 1.0) + ($minutos * 0.01) + ($sms * 0.005);
        $precio = max(10, $precio);
    } else {
        $precio = 5 + ($datos * 0.8) + ($minutos * 0.008) + ($sms * 0.004);
        $precio = max(15, $precio);
    }
    
    return round($precio);
}

function determinarPlanRecomendado($datos, $minutos, $sms) {
    if ($datos <= 5) {
        return 'Plan Básico';
    } elseif ($datos <= 15) {
        return 'Plan Estándar';
    } elseif ($datos <= 30) {
        return 'Plan Avanzado';
    } else {
        return 'Plan Ilimitado';
    }
}

function aplicarPromociones($tipo, $datos, $precio) {
    $descuento = 0;
    $mensaje = '';
    
    if ($tipo === 'pospago' && $datos >= 10) {
        $descuento = $precio * 0.1;
        $mensaje = '¡Obtén el doble de datos por los primeros 6 meses!';
    }
    
    if ($tipo === 'prepago' && $datos >= 5) {
        $descuento = 2;
        $mensaje = 'Recibe $5 de saldo adicional en tu primera recarga';
    }
    
    return [
        'descuento' => $descuento,
        'mensaje' => $mensaje
    ];
}
?>